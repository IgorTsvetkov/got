<?php

namespace app\controllers;

use app\models\Cell;
use app\models\User;
use app\models\Player;
use app\models\GameSession;
use yii\filters\AccessControl;

class GotController extends \yii\web\Controller
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
        $user = User::Me();
        $game = $user->getLastGame()->joinWith(["players", "players.hero", "players.user" => function ($query) {
            $query->select("id,username");
        }])->asArray()->one();
        if (empty($game) || $game->isFinished)
            return $this->redirect("/");
        $player = $user->getLastPlayer();
        $player_id = $player->id;
        $this->layout = false;
        return $this->render('index', compact("game", "player_id"));
    }
    public function actionMove(int $player_id)
    {
        $player = Player::findOne($player_id);
        $player->position += 1;
        $player->position %= 40;
        $player->update();

        $nextTurnPlayer = Player::find()
        ->orderBy(["slot" => SORT_ASC])
        ->where(["game_session_id"=>$player->game_session_id])
        ->andWhere([">", "slot", $player->slot])
        ->limit(1)
        ->one();
        if (empty($nextTurnPlayer))
            $nextTurnPlayer = Player::find()->orderBy(["slot" => SORT_ASC])->limit(1)->one();
        $game = GameSession::findOne($player->game_session_id);
        $game->turn_player_id = $nextTurnPlayer->id;
        $game->update();
        return $this->asJson(["position" => $player->position, "turn_player_id" => $game->turn_player_id]);
    }
}
