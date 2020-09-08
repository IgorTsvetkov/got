<?php

namespace app\controllers;

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

class AuctionController extends \yii\web\Controller
{
    public function actionStart($type, $id)
    {
        $my_user_id = Yii::$app->user->id;
        // $types=["property","tax","utility"];
        $model = self::getTypedModel($type, $id);
        $startCost = $model->cost;
        $name = $model->name;
        $auctionPlayers = function ($query) use ($startCost, $my_user_id) {
            $query->where([">=", "money", $startCost]);
            $query->andWhere(["not in", "user_id", $my_user_id]);
        };
        /**
         * @var GameSession
         */
        $gameSession = GameSession::me()->with(["players" => $auctionPlayers, "auction.maxBetPlayer"])->one();
        if (isset($gameSession->auction))
            throw new Exception("Auction already started");

        $players = $gameSession->players;

        /** @var Player */
        foreach ($players as $player) {
            $player->in_auction = YesNo::YES;
            $player->update(false);
        }
        $auction = new Auction();
        $auction->target_type = $type;
        $auction->target_id = $id;
        $auction->target_name = $name;
        $auction->cost = $startCost;
        $auction->game_session_id = $gameSession->id;
        $auction->turn_player_id = $players[0]->id;
        $auction->save(false);

        $data = [
            "players" => $players,
            "auction" => $auction,
        ];
        return ResponseHelper::Socket("players-and-auction", $data);
    }
    public function actionBet(int $cost)
    {
        /** @var Player */
        $player = Player::me()->with("auction")->one();
        if (!$player || $player->in_auction==YesNo::NO ||!$player->auction)
            throw new Exception("Access denied. Auction involving is impossible");
        $auction=$player->auction;
        if($player->canPay($cost)&&$cost>=$auction->cost){
            $auction->cost=$cost;        
            $auction->update(false);
        }
    }
    public function actionFinish(){
        $player = Player::me()->with("auction")->one();
        /** @var Auction */
        $auction = $player->auction;
        $type=$auction->target_type;
        $id=$auction->target_id;
        $auction->delete();
        return $this->redirect($type."-game-status/buy",["id"=>$id]);        
    }
    public function actionLeave($player_id)
    {
        $player = Player::findOne($player_id);
        if ($player->user_id !== Yii::$app->user->id)
            throw new Exception("Access denied");
        $player->in_auction = YesNo::NO;
        $player->update(false);
        /** @var Auction */
        $auction = Auction::find()->where(["turn_player_id" => $player->id])->with(["gameSession", "activePlayers"])->one();
        $activePlayers = $auction->activePlayers;
        if (empty($activePlayers)) {
            /** @var GameSession */
            $game = $auction->gameSession;
            $game->turn_stage = TurnStageHelper::FINISHED;
            $game->update(false);
            $data = ["game" => ["turn_stage" => $game->turn_stage]];
            $auction->delete();
            //нужно еще сообщение в чат
            return ResponseHelper::Socket("game", $data);            
        }
        if (count($activePlayers) == 1){
            $auction->turn_player_id=$auction->max_bet_player_id;
            $auction->is_finished=YesNo::YES;
            $auction->update(false);
            $data=["auction"=>$auction];
            return ResponseHelper::Socket("auction",$data);
            // return $this->redirect([$auction->target_type . "-game-status/create", "id" => $auction->target_id]);
        }
        $player = $player->getNextTurnPlayer($activePlayers);
        $auction->turn_player_id = $player_id;
        $auction->update(false);
        return ResponseHelper::Success("");
    }
    public static function getTypedModel($type, $id,$loadGameStatus=false)
    {
        $query = null;
        switch ($type) {
            case 'property':
                $query = Property::find();
                break;
            case 'tax':
                $query = Tax::find();
                break;
            case 'utility':
                $query = Utility::find();
                break;
            default:
                throw new Exception("Cand start Auction for " . $type);
                break;
        }
        $query->where(["id"=>$id]);
        if($loadGameStatus)
            $query->with($type."GameStatus");
        $model=$query->one();
        return $model;
    }
}
