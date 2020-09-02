<?php

namespace app\controllers;

use app\models\Cell;
use app\models\User;
use app\models\Player;
use yii\web\Controller;
use app\models\GameSession;
use yii\filters\AccessControl;
use app\helpers\ResponseHelper;
use app\helpers\TurnStageHelper;
use app\helpers\YesNo;
use Exception;

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
        $user = User::me();
        $userSafeQuery = function ($query) {
            $query->select("id,username");
        };
        $game = $user->getLastGame()
            ->joinWith(["players", "players.hero", "players.user" => $userSafeQuery, "players.propertyCells" => function ($q) {
                $q->select("id,property_id,position");
            }])
            ->andWhere(["finished_at" => null])
            ->asArray()
            ->one();
        if (empty($game))
            return $this->redirect("/");
        $player = $user->getLastPlayer();
        $player_id = $player->id;
        $this->layout = false;
        return $this->render('index', compact('game', 'player_id'));
    }
    public function actionMove(int $player_id)
    {
        $step = 1;
        /** @var Player */
        $player = Player::find()->where(["id" => $player_id])->with("gameSession")->limit(1)->one();
        $game = $player->gameSession;
        if ($game->turn_stage >=TurnStageHelper::DICE_ROLLED)
            throw new Exception("вы не можете преместиться дважды за один ход");
        $player->move($step);

        $game->turn_stage = TurnStageHelper::DICE_ROLLED;
        $game->update();
        $data = [
            "player" => $player,
            "game" => $game,
            "step" => $step
        ];
        return ResponseHelper::Socket("move", $data);
    }
    public function actionEndTurn($player_id)
    {
        $player = Player::find()->where(["id" => $player_id])->with("gameSession")->limit(1)->one();
        $nextTurnPlayer = $player->getNextTurnPlayer();
        /** @var GameSession */
        $game = $player->gameSession;
        $game->turn_player_id = $nextTurnPlayer->id;
        //сброс настроек для следующего игрока
        $game->turn_stage = TurnStageHelper::BEGIN;
        
        $game->update();
        $data = ["game" => $game];
        return ResponseHelper::Socket("end-turn", $data);
    }
}
