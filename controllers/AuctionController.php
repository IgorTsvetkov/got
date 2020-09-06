<?php

namespace app\controllers;

use Yii;
use Exception;
use app\models\Tax;
use app\helpers\YesNo;
use app\models\Player;
use app\models\Utility;
use app\models\Property;
use app\models\GameSession;
use app\helpers\ResponseHelper;
use app\helpers\TurnStageHelper;
use app\models\Auction;

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
        $gameSession = GameSession::me()->with(["players" => $auctionPlayers, "auction"])->one();
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
        $auction->turn_player_id = $players[0]->id;
        $auction->game_session_id = $gameSession->id;
        $auction->save(false);

        $data = [
            "players" => $players,
            "auction" => $auction,
        ];
        return ResponseHelper::Socket("players-and-auction", $data);
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
        if (empty($activePlayers))
        {
            /** @var GameSession */
            $game=$auction->gameSession;
            $game->turn_stage=TurnStageHelper::FINISHED;
            $game->update(false);
            $data=["game"=>["turn_stage"=>$game->turn_stage]];
            //нужно еще сообщение в чат
            return ResponseHelper::Socket("game",$data);
        }
        if (count($activePlayers) == 1)
            return $this->redirect([$auction->target_type."-game-status/create","id"=>$auction->target_id]);
        $player = $player->getNextTurnPlayer($activePlayers);
        $auction->turn_player_id = $player_id;
        $auction->update(false);
        return ResponseHelper::Success("");
    }
    public static function getTypedModel($type, $id)
    {
        $typedModel = null;
        switch ($type) {
            case 'property':
                $typedModel = Property::findOne($id);
                break;
            case 'tax':
                $typedModel = Tax::findOne($id);
                break;
            case 'utility':
                $typedModel = Utility::findOne($id);
                break;
            default:
                throw new Exception("Cand start Auction for " . $type);
                break;
        }
        return $typedModel;
    }
}
