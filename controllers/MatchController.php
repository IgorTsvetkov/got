<?php

namespace app\controllers;

use Yii;
use app\models\User;
use yii\helpers\VarDumper;
use app\models\GameSession;
use app\models\Player;
use yii\filters\AccessControl;

class MatchController extends \yii\web\Controller
{
    public function behaviors()
    {
        return[
            [
                'class'=>AccessControl::className(),
                'rules'=>[
                    [
                    'allow'=>false,
                    'roles'=>['?']
                    ],
                    [
                        'allow'=>true,
                        'roles'=>['@']
                    ]
                ]
            ]
        ];
    }
    public function beforeAction($action)
    {
        $actions=["index","create-lobby","join"];
        if(in_array($action->id,$actions))
        {
            if(Yii::$app->user->isGuest)
                return $this->redirect("/site/login");
            $user=User::me();
            $lastGame=$user->getLastGame();
            if($lastGame&&$lastGame->isStarted===false){
                return $this->redirect("/match/connect");
            }            
            if($lastGame&&$lastGame->isFinished===false)
                return $this->redirect(["/got/game"]);
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
        return $this->redirect("/match/connect");
    }
    public function actionJoin($game_id){
        $game=GameSession::findOne($game_id);        
        $user=User::me();
        $game->setUserInSlot($user,2);
        
        return $this->redirect("/match/connect");
    }
    public function actionConnect($isJson=false)
    {   $user=User::Me();
        $game=$user->getLastGame();
        if(!$game)
            return $this->redirect("/match");
        
        $users=array_map(function($user){
            return[
            "id"=>$user->id,
            "username"=>$user->username,
            "slot"=>$user->lastPlayer->slot
            ];
        },$game->users);
        $users=usort($users,function($a,$b){return $a->slot>$b->slot?1:-1;});
        $data=[
            'game_id'=>$game->id,
            'owner_id'=>$game->players[0]->user_id,
            'users'=>json_encode($users)
        ];
        if($isJson)
            return $this->asJson($data);
        return $this->render('create',$data);
    }
    public function actionLeft(int $game_id){
        $user=User::me();
        $game=$user->getLastGame();
        if($game->id==$game_id){
            $game->removeUser($user);
        }
        return $this->redirect("/site");
    }
    public function actionChangeSlot(int $slot){
        $user=User::Me();
        $game=$user->getLastGame();
        $data=null;
        if($game->IsStarted){
            $data=["error"=>["message"=>"you can't change slot after game have been started"]];
        }
        elseif(Player::find(["game_session_id"=>$game->id])->where(["slot"=>$slot])->limit(1)->one()){
            $data=["error"=>["message"=>"Этот слот занят другим игроком"]];
        }
        elseif(Player::find(["game_session_id"=>$game->id])->where(["slot"=>$slot])->limit(1)->one()===null){
            $player=$user->getLastPlayer();
            $player->slot=$slot;
            $player->save();
            $data=["success"=>true];
        }
        // return $this->redirect("/match/connect-json");
        return $this->asJson($data);
    }
}
