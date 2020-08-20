<?php

/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace app\commands;

use app\helpers\SocketController;
use Yii;
use app\models\User;
use app\models\Player;
use yii\console\ExitCode;
use app\models\UserSocket;
use app\models\GameSession;
use yii\console\Controller;
use app\websockets\MyWorker;
use app\websockets\MyTcpConnection;
use app\websockets\MyUdpConnection;
use yii\behaviors\TimestampBehavior;
use Workerman\Connection\TcpConnection;


/**
 * This command echoes the first argument that you have entered.
 *
 * This command is provided as an example for you to learn how to create console commands.
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class PreMatchController extends SocketController
{   
    public function actionAuth()
    {
        $this->startWebSocket("/auth",function($user,$connection,$data,$worker){
            $connection->sendEncoded(["message"=>$data->message]);
        });                
    }
    public function actionSendToAll(){
        $this->startWebSocket("/send-to-all",function($user,$currentConnection,$data,$worker){   
            foreach ($worker->connections as $connection) {
                // var_dump($data->uid);
                if(isset($connection->uid)&&$connection->uid==$data->uid){
                    var_dump("uid");
                    var_dump($connection->uid);
                    $connection->sendEncoded($data);
                }
                // if($connection->uid=)
                // var_dump("connections count");
                // var_dump(count($worker->connections));
            }
        });
    }
    public function actionStart(){
        // $this->startWebSocket("/start",function(User $user,$connection,$data){
        //     $game=$user->getLastGame()->one();

        //     if($game-){
        //         $connection->sendEncoded([
        //             "message"=>"You already have active game session"
        //         ]);
        //         return;
        //     }
        //     $gameSession=new GameSession();
        //     $gameSession->save();
        //     $player=new Player();
        //     $player->position=0;
        //     $player->slot=1;
        //     $player->save();
        //     $gameSession->link("players",$player);
        //     $gameSession->link("users",$user);

        //     $connection->sendEncoded([]);
        // });
    }
    // public function actionPick()
    // {
    //     $this->startWebSocket("/pick",function($user,$connection,$data){
    //         $gameSession=GameSession::findOne($user->session_id);
    //         if($gameSession&&!isset($gameSession[$data->playerSlot])){
    //             $gameSession[$data->playerSlot]=$user->id;
    //             $gameSession->save();
    //             $connection->sendEncoded(
    //             [
    //                 "gameSession"=>$gameSession->attributes,
    //             ]);
    //             return;
    //         }
    //         $connection->sendEncoded([]);
    //     });
    // }

    public function actionX(){
        $nextTurnPlayer=Player::find()->orderBy(["slot"=>SORT_ASC])->where([">","slot",4])->limit(1)->one();
        if(empty($nextTurnPlayer))
            $nextTurnPlayer=Player::find()->orderBy(["slot"=>SORT_ASC])->limit(1)->one();

        var_dump($nextTurnPlayer);
    }
}
