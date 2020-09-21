<?php

namespace app\controllers;

use app\helpers\EstateManager;
use app\helpers\EstateTypeHelper;
use app\helpers\exceptions\ActionDeniedException;
use Yii;
use Exception;
use app\helpers\YesNo;
use app\models\Player;
use app\models\Auction;
use app\models\estate\Tax;
use app\models\GameSession;
use app\models\estate\Utility;
use app\helpers\ResponseHelper;
use app\models\estate\Property;
use app\helpers\TurnStageHelper;
use Symfony\Component\Finder\Exception\AccessDeniedException;
use yii\helpers\VarDumper;

class AuctionController extends \yii\web\Controller
{
    public function actionStart($type_id, $id)
    {
        $my_user_id = Yii::$app->user->id;
        // $types=["property","tax","utility"];
        $manager =new  EstateManager($type_id);
        $model=$manager->getEstate($id);
        $startCost = $model->cost;
        $name = $model->name;
        $auctionPlayersQuery = function ($query) use ($startCost, $my_user_id) {
            $query->where([">=", "money", $startCost]);
            $query->andWhere(["not in", "user_id", $my_user_id]);    
        };
        /**
         * @var GameSession
         */
        $game = GameSession::me()->with(["players" => $auctionPlayersQuery])->one();
        if (isset($game->auction))
            throw new ActionDeniedException("Auction already started");
        $actionPlayers = $game->players;
        if (empty($actionPlayers)) {
            $game->turn_stage = TurnStageHelper::FINISHED;
            $game->update(false);
            $data = ["game" => $game,"auction"=>false];
            return ResponseHelper::Socket("auction", $data);
        }

        $players = $game->players;

        /** @var Player */
        foreach ($players as $player) {
            $player->in_auction = YesNo::YES;
            $player->update(false);
        }
        $auction = new Auction();
        $auction->estate_type_id = $type_id;
        $auction->estate_id = $id;
        $auction->estate_name = $name;
        $auction->cost = $startCost;
        $auction->game_session_id = $game->id;
        $auction->turn_player_id = $players[0]->id;
        $auction->save(false);

        $data = [
            "players" => $players,
            "auction" => $auction->getAttributes(),
        ];
        return ResponseHelper::Socket("auction-start", $data);
    }
    public function actionBet(int $cost)
    {
        /** @var Player */
        $player = Player::me()->with(["gameSession", "auction.activePlayers"])->one();
        if (!$player || $player->in_auction == YesNo::NO || !$player->auction)
            throw new ActionDeniedException("You can't involve in this auction");
        /** @var Auction */
        $auction = $player->auction;
        $auctionPlayers = $auction->activePlayers;
        if ($cost < $auction->cost) {
            throw new ActionDeniedException("Cost is too low");
        }
        if (!$player->canPay($cost))
            throw new ActionDeniedException("Player don't have enough money.");
        $getIds=function($player){
            return $player->id;
        };
        $ids=array_map($getIds,$auctionPlayers); 
        $nextTurnPlayer=$player->getNextTurnPlayer(Player::find()->where(["in","id",$ids]));

        $auction->cost = $cost;
        $auction->max_bet_player_id = $player->id;
        $auction->turn_player_id = $nextTurnPlayer->id;
        if(count($auctionPlayers)===1)
        {
            $auction->is_finished=YesNo::YES;
        }
        $auction->update(false);
        $data = ["auction" => $auction];
        return ResponseHelper::Socket("auction", $data);
    }
    public function actionLeave($player_id)
    {
        $player = Player::findOne($player_id);
        if ($player->user_id !== Yii::$app->user->id)
            throw new AccessDeniedException();
        $player->in_auction = YesNo::NO;
        $player->update(false);
        /** @var Auction */
        $auction = Auction::find()->where(["turn_player_id" => $player->id])->with(["gameSession", "activePlayers"])->one();
        $auctionPlayers = $auction->activePlayers;
        if (empty($auctionPlayers)) {
            /** @var GameSession */
            $game = $auction->gameSession;
            $game->turn_stage = TurnStageHelper::FINISHED;
            $game->update(false);
            $auction->delete();
            $data = [
                "game" => ["turn_stage" => $game->turn_stage],
                "chatHelp" => ["message" => "покинул аукцион. Аукцион не состоялся"],
            ];
            //нужно еще сообщение в чат
            return ResponseHelper::Socket("auction-leave", $data);
        } else if (count($auctionPlayers) == 1 && $auction->isMaxBetPlayer($auctionPlayers[0])) {
            $auction->turn_player_id = $auction->max_bet_player_id;
            $auction->is_finished = YesNo::YES;
            $auction->update(false);
            $data = ["auction" => $auction];
            return ResponseHelper::Socket("auction", $data);
            // return $this->redirect([$auction->target_type . "-game-status/create", "id" => $auction->target_id]);
        }
        $player = $player->getNextTurnPlayer($auctionPlayers);
        $auction->turn_player_id = $player_id;
        $auction->update(false);
        $data=["auction"=>$auction];
        return ResponseHelper::Success("auction",$data);
    }
    public function actionBuy($player_id)
    {
        /** @var Auction */
        $auction = Auction::find()->where(["max_bet_player_id" => $player_id])->with(["gameSession", "maxBetPlayer"])->one();
        $player = $auction->maxBetPlayer;
        /** @var GameSession */
        $game=$auction->gameSession;
        $manager =new  EstateManager($auction->estate_type_id);
        $estate=$manager->getEstate($auction->estate_id);
        $cost = $auction->cost;
        if (!$player->canPay($cost))
            return ResponseHelper::Error("Недостаточно средств");
        $estateGameStatus = $player->buy($estate, $cost);
        $auction->delete(false);
        $game->turn_stage=TurnStageHelper::FINISHED;
        $game->update(false);
        //дублируется код  в CommonStateController actionBuy  
        $data = [
            "player" => $player,
            "game" => $game,
            "estate" => $estateGameStatus,
            "chatHelp" => ["estate_type_id" => $estateGameStatus->estate_type_id, "estate_id" => $estateGameStatus->estate_id]
        ];
        return ResponseHelper::Socket("estate-bought", $data);
    }
}
