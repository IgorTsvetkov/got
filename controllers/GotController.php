<?php

namespace app\controllers;

use app\models\Cell;
use app\models\User;
use app\models\Player;
use yii\web\Controller;
use app\models\GameSession;
use yii\filters\AccessControl;
use app\helpers\ResponseHelper;

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
        $step=1;
        /** @var Player */
        $player = Player::find()->where(["id"=>$player_id])->with("gameSession")->limit(1)->one();
        $player->position += $step;
        $player->position %= 40;
        $player->update();

        $game=$player->gameSession;
        $game->is_dice_rolled=1;
        $game->update();
        return $this->asSocketJson("move",["is_dice_rolled"=>$game->is_dice_rolled,"player_id"=>$player->id,"position" => $player->position,"step"=>$step]);
    }
    public function actionEndTurn($player_id)
    {
        $player = Player::find()->where(["id"=>$player_id])->with("gameSession")->limit(1)->one();        
        $nextTurnPlayer = $player->getNextPlayer();
        /** @var GameSession */
        $game =$player->gameSession;
        $game->turn_player_id = $nextTurnPlayer->id;
        //сброс настроек для следующего игрока
        $game->is_dice_rolled = 1;
        $game->is_action_done = 0;
        $game->update();

        return $this->asSocketJson("end-turn",["is_action_done"=>$game->is_action_done,"is_dice_rolled"=>$game->is_dice_rolled,"turn_player_id" => $game->turn_player_id]);

    }
}
