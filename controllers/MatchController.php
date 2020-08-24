<?php

namespace app\controllers;

use Yii;
use app\models\User;
use app\models\GameSession;
use app\models\Player;
use yii\filters\AccessControl;
use yii\helpers\VarDumper;

class MatchController extends \app\controllers\MainController
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
            if ($lastGame && ($lastGame->isStarted === false||$lastGame->isFinished === false)) {
                return $this->redirect("/match/connect");
            }
        }
        return parent::beforeAction($action);
    }
    public function actionIndex($json=false)
    {
        $user=User::me();
        $lastGame=$user->getLastGame()->one();
        if($lastGame && !$lastGame->isFinished)
            $this->redirect("/match/connect");
        if($json){
            $games = GameSession::find()
            ->select("game_session.id,game_session.name,user.username,COUNT(player.id) as count,game_session.created_at,game_session.started_at,game_session.finished_at")
            ->where(["started_at" => null,"finished_at"=>null])
            ->orderBy(["created_at" => SORT_DESC])
            ->joinWith(
                [
                    "leader"=>function($query){
                $query->select("id,username");
            },
            "players"=>function($query){
                $query->select("id,game_session_id");
            }])
            ->groupBy("game_session.id")
            ->all();
            return $this->asJson($games);
        }
            
        return $this->render('index');
    }
    public function actionCreateLobby()
    {
        $user = User::me();
        $game = new GameSession();
        $game->leader_user_id = $user->id;
        $game->save();
        $player = Player::createAndLink($game, $user);
        $player->save();
        
        //дублируется запрос, но здесь только одно поле
        $game = GameSession::find()
        ->select("game_session.id,game_session.name,user.username,COUNT(player.id) as count,game_session.created_at,game_session.started_at,game_session.finished_at")
        ->where(["started_at" => null,"finished_at"=>null])
        ->orderBy(["created_at" => SORT_DESC])
        ->joinWith(
            [
                "leader"=>function($query){
            $query->select("id,username");
        },
        "players"=>function($query){
            $query->select("id,game_session_id");
        }])
        ->groupBy("game_session.id")
        ->orderBy(["id"=>SORT_DESC])
        ->one();

        return $this->asSocketJson("create-lobby",["game"=>$game]);
    }
    public function actionJoin($game_id)
    {
        $game = GameSession::findOne($game_id);
        $user = User::me();
        $player = Player::createAndLink($game, $user);
        $player->save();

        // return $this->redirect("/match/connect");
        return $this->asSocketJson("join",$player);
    }
    public function actionConnect($json = false)
    {
        $user = User::Me();
        $game = $user->getLastGame()->joinWith(["players","players.hero", "players.user" => function ($query) {
            $query->select(["id", "username"]);
        }])
            ->asArray()
            ->one();
        if ($game && ($game["started_at"] && $game["finished_at"] == false)) {
            return $this->redirect("/got/game");
        }
        if ($json)
            return $this->asJson($game);
        return $this->render('create', compact("game"));
    }

    public function actionLeave(int $game_id)
    {
        $players=Player::find()->where(["game_session_id"=>$game_id])->with(["user","gameSession"])->all();
        $player=array_pop(array_filter($players,function($player){
            return $player->user->id===Yii::$app->user->id;
        }));
        $user=$player->user;
        $game=$player->gameSession;
        if ($game->id == $game_id) {
            $game->removeUser($user->id);
            if($game->turn_player_id==$player->id){
                $nextTurnPlayer=$player->getNextTurnPlayer();
                if(isset($nextTurnPlayer))
                {
                    $game->turn_player_id=$nextTurnPlayer->id;
                    var_dump($nextTurnPlayer->user->id);
                    $game->leader_user_id=$nextTurnPlayer->user->id;
                    $game->update();
                    
                    // return $this->asSocketJson("next-turn",[""]);
                }
            }
        // if($game->leader_user_id===$user->id){
        //     $game->leader_user_id=User::find()->with("game")where(""=>)
        // }
            $playersCount=count($players)-1;
            if($playersCount===0){
                $game->touch("finished_at");
                $game->update();
            }
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
        } elseif (Player::find()->where(["game_session_id" => $game->id])->andWhere(["slot" => $slot])->limit(1)->one()) {
            $data = ["error" => ["message" => "Этот слот уже занят игроком"]];
        } elseif (Player::find()->where(["game_session_id" => $game->id])->andWhere(["slot" => $slot])->limit(1)->one() === null) {
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
