<?php

/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace app\commands;
use Yii;
use app\models\Player;
use yii\console\ExitCode;
use app\models\UserSocket;
use app\models\GameSession;
use yii\console\Controller;
use app\websockets\MyWorker;
use app\websockets\MyTcpConnection;
use app\websockets\MyUdpConnection;
use yii\behaviors\TimestampBehavior;

require_once __DIR__ . '/../vendor/autoload.php';
/**
 * This command echoes the first argument that you have entered.
 *
 * This command is provided as an example for you to learn how to create console commands.
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class PreMatchController extends Controller
{    
    public function beforeAction($action)
    {
        return parent::beforeAction($action);
    }
    public function actionChangeSlot(){
        $worker = new MyWorker("websocket://127.0.0.1:8989/change-slot");
        $worker->count = 1;
        $worker->onMessageDecoded = function (MyTcpConnection $connection, $data) use ($worker) {
            $user=UserSocket::getUserByAuthInfo($data->authInfo);
            var_dump($user->id);
            $slot=$data->slot;


            if ($user) {
                $game=$user->getLastGame();
                if($game->getIsStarted()){
                    $error=["message"=>"you can't change slot after game have been started"]; 
                    $connection->sendEncoded([
                        "status code" => 200,
                        "error"=>$error
                    ]);
                    return;
                }
                if(Player::find(["game_session_id"=>$game->id])->where(["slot"=>$slot])->limit(1)->one()){
                    $error=["message"=>"Этот слот уже занят игроком"];
                    $connection->sendEncoded([
                        "status code" => 200,
                        "error"=>$error
                    ]);
                    return;
                }
                if(Player::find(["game_session_id"=>$game->id])->where(["slot"=>$slot])->limit(1)->one()===null){
                    $player=$user->getLastPlayer();
                    $player->slot=$slot;
                    $player->save();
                }
                $gameInfo=[
                    'game_id'=>$game->id,
                    'users'=>array_map(function($user){
                        return[
                            "username"=>$user->username,
                            "slot"=>$user->lastPlayer->slot
                        ];
                    },$game->users)
                ];

                foreach ($worker->connections as $connection) {
                    $connection->sendEncoded([
                        "status code" => 200,
                        "data"=>$gameInfo
                    ]);
                }
 
            } else {
                var_dump("auth false");
                $connection->sendEncoded([
                    "status code" => 401
                ]);
            }
        };
        MyWorker::runAll();
        
    }
    public function actionStart(){
        $worker = new MyWorker("websocket://127.0.0.1:8989/start");
        $worker->count = 1;
        $worker->onMessageDecoded = function (MyTcpConnection $connection, $data) use ($worker) {
            $user=UserSocket::getUserByAuthInfo($data->authInfo);
            var_dump($user->id);
            if ($user) {
                $activeSession=$user->activeGameSession;
                if($activeSession)
                    $connection->sendEncoded([
                        "status code" => 200,
                        "message"=>"You already have active game session"
                    ]);
                $gameSession=new GameSession();
                $gameSession->save();
                $player=new Player();
                $player->position=0;
                $player->slot=1;
                $player->save();
                $gameSession->link("players",$player);
                $gameSession->link("users",$user);



                $connection->sendEncoded([
                    "status code" => 200
                ]);
            } else {
                var_dump("auth false");
                $connection->sendEncoded([
                    "status code" => 401
                ]);
            }
        };
        MyWorker::runAll();
    }
    public function actionPick()
    {
        $worker = new MyWorker("websocket://127.0.0.1:8989/pick");
        $worker->count = 1;
        $worker->onMessageDecoded = function (MyTcpConnection $connection, $data) use ($worker) {
            $user=UserSocket::getUserByAuthInfo($data->authInfo);
            var_dump($user->id);
            if ($user) {
                $gameSession=GameSession::findOne($user->session_id);
                if($gameSession&&!isset($gameSession[$data->playerSlot])){
                    $gameSession[$data->playerSlot]=$user->id;
                    $gameSession->save();
                    $connection->sendEncoded(
                    [
                        "gameSession"=>$gameSession->attributes,
                    ]);
                }
                $connection->sendEncoded([
                    "status code" => 200
                ]);
            } else {
                var_dump("auth false");
                $connection->sendEncoded([
                    "status code" => 401
                ]);
            }
        };
        MyWorker::runAll();
    }
}
