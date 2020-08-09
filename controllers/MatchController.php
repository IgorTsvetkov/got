<?php

namespace app\controllers;

use Yii;
use app\models\User;
use yii\helpers\VarDumper;
use app\models\GameSession;
use app\models\Player;

class MatchController extends \yii\web\Controller
{
    public function beforeAction($action)
    {
        $actions=["index","create-lobby","join-to-lobby"];
        if(in_array($action->id,$actions))
        {
            if(Yii::$app->user->isGuest)
                return $this->redirect("/site/login");
            $user=User::me();
            $lastGame=$user->getLastGame();
            if($lastGame&&$lastGame->isStarted===false){
                return $this->redirect(["/match/reconnect-to-lobby","game_id"=>$lastGame->id]);
            }

            if($lastGame&&$lastGame->isFinished===false)
                return $this->redirect(["/got/game",]);
        }
        return parent::beforeAction($action);
    }
    public function actionIndex()
    {
        $games=GameSession::find()
        ->where(["started_at"=>null])
        ->orderBy(["created_at"=>SORT_DESC])
        ->with("users")
        ->all();
        return $this->render('index',compact("games"));
    }
    public function actionCreateLobby(){
        $user=User::me();
        $game=new GameSession();
        $game->save();
        $game->setUserInSlot($user,1);
        return $this->render('create',[
            'session_id'=>$game->id,
            'usernames'=>json_encode(array_map(fn($user)=>$user->username,$game->users))
        ]);
    }
    public function actionJoinToLobby($game_id){
        $user=User::me();
        $game=GameSession::findOne($game_id);            
        $game->setUserInSlot($user,2);
    }
    public function actionReconnectToLobby(int $game_id)
    {   
        $game=GameSession::findOne($game_id);
        return $this->render('create',[
            'session_id'=>$game->id,
            'usernames'=>json_encode(array_map(fn($user)=>$user->username,$game->users))
            ]);
    }
    public function actionLeftLobby($game_id){
        $game=GameSession::findOne($game_id);
        $game->removeUser(User::me());
        return $this->render('create',[
            'session_id'=>$game->id,
            'usernames'=>json_encode(array_map(fn($user)=>$user->username,$game->users))
        ]);
    }
}
