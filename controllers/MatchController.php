<?php

namespace app\controllers;

use Yii;
use app\models\User;
use yii\helpers\VarDumper;
use app\models\GameSession;
use app\models\Player;
use PHPUnit\Framework\MockObject\Builder\Identity;
use yii\filters\AccessControl;

class MatchController extends \yii\web\Controller
{
    public function behaviors()
    {
        return [
            [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'allow' => false,
                        'roles' => ['?']
                    ],
                    [
                        'allow' => true,
                        'roles' => ['@']
                    ]
                ]
            ]
        ];
    }
    public function beforeAction($action)
    {
        $actions = ["index", "create-lobby", "join"];
        if (in_array($action->id, $actions)) {
            if (Yii::$app->user->isGuest)
                return $this->redirect("/site/login");
            $user = User::me();
            $lastGame = $user->getLastGame()->one();
            if ($lastGame && $lastGame->isStarted === false) {
                return $this->redirect("/match/connect");
            }
            if ($lastGame && $lastGame->isFinished === false)
                return $this->redirect(["/got/game"]);
        }
        return parent::beforeAction($action);
    }
    public function actionIndex()
    {
        $games = GameSession::find()
            ->where(["started_at" => null])
            ->orderBy(["created_at" => SORT_DESC])
            ->with("users")
            ->all();
        return $this->render('index', compact("games"));
    }
    public function actionCreateLobby()
    {
        $user = User::me();
        $game = new GameSession();
        $game->leader_user_id = $user->id;
        $game->save();
        $player = Player::createAndLink($game, $user);
        $player->save();

        return $this->redirect("/match/connect");
    }
    public function actionJoin($game_id)
    {
        $game = GameSession::findOne($game_id);
        $user = User::me();
        $player = Player::createAndLink($game, $user);
        $player->save();

        return $this->redirect("/match/connect");
    }
    public function actionConnect($json = false)
    {
        $user = User::Me();
        $game = $user->getLastGame()->joinWith(["players" => function ($query) {
        }, "players.hero", "players.user" => function ($query) {
            $query->select(["id", "username"]);
        }])
            ->asArray()
            ->one();
        if (!$game || $game["started_at"] && $game["finished_at"] == false) {
            return $this->redirect("/got/game");
        }
        if ($json)
            return $this->asJson($game);
        return $this->render('create', compact("game"));
    }

    public function actionLeft(int $game_id)
    {
        $user = User::me();
        $game = $user->getLastGame()->one();
        if ($game->id == $game_id) {
            $game->removeUser($user);
        }
        return $this->redirect("/site");
    }
    public function actionChangeSlot(int $slot)
    {
        $user = User::Me();
        $game = $user->getLastGame()->one();
        $data = null;
        if ($game->IsStarted) {
            $data = ["error" => ["message" => "you can't change slot after game have been started"]];
        } elseif (Player::find(["game_session_id" => $game->id])->where(["slot" => $slot])->limit(1)->one()) {
            $data = ["error" => ["message" => "Этот слот уже занят игроком"]];
        } elseif (Player::find(["game_session_id" => $game->id])->where(["slot" => $slot])->limit(1)->one() === null) {
            $player = $user->getLastPlayer();
            $player->slot = $slot;
            $player->save();
            $data = ["status" => "success"];
        }
        // return $this->redirect("/match/connect-json");
        return $this->asJson($data);
    }
    public function actionStart(int $game_id)
    {
        $user = User::Me();
        $game = GameSession::findOne($game_id);
        if ($game->leader_user_id === $user->id) {
            $game->turn_player_id = $game->players[0]->id;
            $game->update();
            $game->touch("started_at");
        }
        return $this->asJson(["started" => true]);
    }
}
