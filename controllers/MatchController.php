<?php

namespace app\controllers;

use Yii;
use app\models\User;
use app\models\Player;
use yii\web\Controller;
use yii\helpers\VarDumper;
use app\models\GameSession;
use yii\filters\AccessControl;
use app\helpers\ResponseHelper;
use app\helpers\TurnStageHelper;
use yii\db\Expression;

class MatchController extends Controller
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
                return $this->redirect("/");
            $user = User::me();
            $lastGame = $user->getLastGame()->one();
            if ($lastGame && ($lastGame->isStarted === false || $lastGame->isFinished === false)) {
                return $this->redirect("/match/connect");
            }
        }
        return parent::beforeAction($action);
    }
    public function actionIndex($json = false)
    {
        $user = User::me();
        Yii::debug($user);
        $lastGame = $user->getLastGame()->one();
        if ($lastGame && !$lastGame->isFinished)
            $this->redirect("/match/connect");
        if ($json) {
            $games = GameSession::find()
                ->select("game_session.id,
            game_session.name,
            user.username,
            COUNT(player.id) as count,
            game_session.created_at,
            game_session.started_at,
            game_session.finished_at")
                ->where(["started_at" => null, "finished_at" => null])
                ->orderBy(["created_at" => SORT_DESC])
                ->joinWith(
                    [
                        "leader" => function ($query) {
                            $query->select("id,username");
                        },
                        "players" => function ($query) {
                            $query->select("id,game_session_id");
                        },
                    ]
                )
                ->groupBy("game_session.id")
                ->all();
            return ResponseHelper::Success($games);
        }

        return $this->render('index');
    }
    public function actionCreateLobby()
    {
        $user = User::me();
        $hasActiveGame=$user->hasActiveGame();
        if($hasActiveGame)
            return ResponseHelper::Error("Имеет незаконченную игру");
        $game = new GameSession();
        $game->leader_user_id = $user->id;
        $game->save();
        $player = Player::createAndLink($game, $user);
        $player->save();

        //дублируется запрос, но здесь только одно поле
        $game = GameSession::find()
            ->select("game_session.id,
        game_session.name,
        user.username,
        COUNT(player.id) as count,
        game_session.created_at,
        game_session.started_at,
        game_session.finished_at")
            ->where(["started_at" => null, "finished_at" => null])
            ->orderBy(["created_at" => SORT_DESC])
            ->joinWith(
                [
                    "leader" => function ($query) {
                        $query->select("id,username");
                    },
                    "players" => function ($query) {
                        $query->select("id,game_session_id");
                    },
                ]
            )
            ->groupBy("game_session.id")
            ->orderBy(["id" => SORT_DESC])
            ->one();

        return ResponseHelper::Socket("create-lobby", ["game" => $game]);
    }
    public function actionJoin($game_id)
    {
        $game = GameSession::find()->where(["id" => $game_id])->with("players")->one();
        if (GameSession::MAX_PLAYERS <= count($game->players))
            return ResponseHelper::Error("Невозможно подключиться! Лобби игры заполнено");
        $user = User::me();
        $player = Player::createAndLink($game, $user);
        $player->save();

        return ResponseHelper::Socket("join", $player);
    }
    public function actionConnect($json = false)
    {
        $user = User::me();
        $game = $user->getLastGame()->joinWith(["players", "players.hero", "players.user" => function ($query) {
            $query->select(["id", "username"]);
        }])
            ->asArray()
            ->one();
        if ($game && ($game["started_at"] && $game["finished_at"] == false)) {
            return $this->redirect("/got/game");
        }
        if ($json)
            return ResponseHelper::Success($game);
        return $this->render('create', compact("game"));
    }

    public function actionLeave(int $game_id)
    {
        $user_id = Yii::$app->user->id;
        /** @var Player $player */
        $player = Player::find()->where(["game_session_id" => $game_id, "user_id" => $user_id])->with("gameSession")->limit(1)->one();
        $game = $player->gameSession;

        if ($game->id == $game_id) {
            /** @var Player $nextTurnPlayer */
            $nextPlayer = $player->getNextTurnPlayer();
            //если текущий игрок последний, завершить игру
            if ($nextPlayer->id === $player->id) {
                $game->removeUser($user_id);
                $game->touch("finished_at");
                $game->update();
                return ResponseHelper::Redirect("/site");
            }
            //значит игрок не последний, передаем след пользователю права лидера и удаляем текущего игрока
            $game->leader_user_id = $nextPlayer->user->id;
            $game->removeUser($user_id);
            $game->update();
            return ResponseHelper::Socket("leader-change", [""]);
        }
        return ResponseHelper::Redirect("/site");
    }
    public function actionChangeSlot(int $slot)
    {
        $user = User::me();
        $game = $user->getLastGame()->one();
        $data = null;
        if ($game->IsStarted) {
            $data = ["error" => ["message" => "you can't change slot after game have been started"]];
        } elseif (Player::find()->where(["game_session_id" => $game->id])->andWhere(["slot" => $slot])->limit(1)->one()) {
            $data = ["error" => ["message" => "Этот слот уже занят игроком"]];
        } elseif (Player::find()->where(["game_session_id" => $game->id])->andWhere(["slot" => $slot])->limit(1)->one() === null) {
            $player = $user->getLastPlayer();
            $player->slot = $slot;
            $player->save();
            $data = ["status" => "success"];
        }
        // return $this->redirect("/match/connect-json");
        return ResponseHelper::Success($data);
    }
    public function actionStart(int $game_id)
    {
        $user_id = Yii::$app->user->id;
        /** @var Player */
        $anyPlayer = Player::find()
            ->where(["game_session_id" => $game_id])
            ->with("gameSession")
            ->limit(1)
            ->one();
        $game = $anyPlayer->gameSession;
        if ($game->leader_user_id === $user_id) {
            $game->turn_player_id = $anyPlayer->id;
            $game->turn_stage=TurnStageHelper::START_MOVE;
            $game->update();
            $game->touch("started_at");
        }
        return ResponseHelper::Socket("start-game");
    }
}
