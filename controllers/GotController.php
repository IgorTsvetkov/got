<?php

namespace app\controllers;

use app\helpers\GameHelper;
use Exception;
use yii\db\Query;
use app\models\Cell;
use app\models\User;
use app\helpers\YesNo;
use app\models\Player;
use yii\web\Controller;
use app\models\GameSession;
use app\models\TaxGameStatus;
use yii\filters\AccessControl;
use app\helpers\ResponseHelper;
use app\helpers\TurnStageHelper;
use app\models\gamestatus\CommonEstateGameStatus;
use app\models\UtilityGameStatus;
use app\models\PropertyGameStatus;

class GotController extends Controller
{
    public function behaviors()
    {
        return [
            [
                "class" => AccessControl::class,
                "rules" => [
                    [
                        "allow" => false,
                        "roles" => ['?']
                    ],
                    [
                        "allow" => true,
                        "roles" => ['@']
                    ],
                ]
            ]
        ];
    }
    public function actionGame()
    {
        $user=User::me();
        $userSafeQuery = function ($query) {
            $query->select("id,username");
        };
        $playerQuery=function(Query $q)use($userSafeQuery){
            $q->with(["user"=>$userSafeQuery,"estates","hero"]);
        };
        $game = $user->getLastGame()
        ->joinWith(["players"=>$playerQuery, "auction"])
        ->andWhere(["finished_at" => null])
        ->asArray()
        ->one();
        $player = Player::me()->one();
        if (empty($game))
            return $this->redirect("/");
        $player_id = $player->id;
        $this->layout = false;
        return $this->render('index', compact('game', 'player_id'));
    }
    public function actionRollDices(){
        $game = GameSession::meOne();
        $game->roll_count_first=GameHelper::roll();
        $game->roll_count_second=GameHelper::roll();
        $game->update(false);
        return ResponseHelper::Socket("roll",["game"=>$game]);
    }
    public function actionRollDicesFinish()
    {
        /** @var Player */
        $player = Player::me()->with("gameSession")->one();
        $game = $player->gameSession;
        if($game->turn_stage===TurnStageHelper::ROLL_AGAIN)
        {
            $game->turn_stage = TurnStageHelper::ROLL_AGAIN_FINISH;
            $game->update(false);
            $data = ["game" => $game];
            return ResponseHelper::Socket("game", $data);
        }
        if ($game->turn_stage===TurnStageHelper::START_MOVE)
        {
            $player->move($game->getRollSum());
            if($player->canFinishTurn())
                $game->turn_stage = TurnStageHelper::ACTION_CAN_SKIPPED;
            else if($player->isUtilityRollRequired())
                $game->turn_stage = TurnStageHelper::ROLL_AGAIN;
            else
                $game->turn_stage = TurnStageHelper::ACTION_UNSKIP;
                
            $game->update(false);

            $data = [
                "player" => $player,
                "game" => $game,
            ];
            return ResponseHelper::Socket("my-player-and-game", $data);
        }
        throw new Exception("Can't roll");
    }
    public function actionEndTurn($player_id)
    {
        $player = Player::find()->where(["id" => $player_id])->with("gameSession")->limit(1)->one();
        $nextTurnPlayer = $player->getNextTurnPlayer();
        /** @var GameSession */
        $game = $player->gameSession;
        $game->turn_player_id = $nextTurnPlayer->id;
        //сброс настроек для следующего игрока
        $game->resetTurn();
        $game->update(false);
        $data = ["game" => $game];
        return ResponseHelper::Socket("game", $data);
    }

}
