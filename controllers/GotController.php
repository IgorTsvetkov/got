<?php

namespace app\controllers;

use app\models\Cell;
use app\models\User;
use app\models\Player;
use yii\web\Controller;
use app\models\GameSession;
use yii\filters\AccessControl;
use app\helpers\ResponseHelper;
use app\helpers\YesNo;

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
        $userSafeQuery=function ($query) {
            $query->select("id,username");
        };
        $game = $user->getLastGame()
        ->joinWith(["players", "players.hero", "players.user" => $userSafeQuery])
        ->andWhere(["finished_at"=>null])
        ->asArray()
        ->one();
        if (empty($game))
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
        $player->move($step);

        $game=$player->gameSession;
        $game->is_dice_rolled=YesNo::YES;
        $game->update();
        return ResponseHelper::Socket("move",["is_dice_rolled"=>$game->is_dice_rolled,"player_id"=>$player->id,"position" => $player->position,"step"=>$step]);
    }
    public function actionEndTurn($player_id)
    {
        $player = Player::find()->where(["id"=>$player_id])->with("gameSession")->limit(1)->one();        
        $nextTurnPlayer = $player->getNextPlayer();
        /** @var GameSession */
        $game =$player->gameSession;
        $game->turn_player_id = $nextTurnPlayer->id;
        //сброс настроек для следующего игрока
        $game->is_dice_rolled = YesNO::NO;
        $game->is_action_done = YesNo::NO;
        $game->update();
        // $data=["game"=>[
        //     "is_action_done"=>$game->is_action_done,
        //     "is_dice_rolled"=>$game->is_dice_rolled,
        //     "turn_player_id" => $game->turn_player_id
        // ]];
        $data=[
            "is_action_done"=>$game->is_action_done,
            "is_dice_rolled"=>$game->is_dice_rolled,
            "turn_player_id" => $game->turn_player_id];
        return ResponseHelper::Socket("end-turn",$data);

    }
}
