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
use Workerman\Connection\TcpConnection;
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
    public $socketPath="websocket://127.0.0.1:8989";
    public function beforeAction($action)
    {

        return parent::beforeAction($action);
    }
    public function actionAuth()
    {
        $this->startWebSocket("/auth",function($user,$connection,$data,$worker){
            $connection->sendEncoded(["message"=>$data->message]);
        });                
    }
    public function actionSendToAll(){
        $this->startWebSocket("/send-to-all",function($user,$connection,$data,$worker){   
            foreach ($worker->connections as $connection) {
                $connection->sendEncoded($data);
            }
        });
    }
    public function actionStart(){
        $this->startWebSocket("/start",function($user,$connection,$data){
            $activeSession=$user->activeGameSession;
            if($activeSession){
                $connection->sendEncoded([
                    "message"=>"You already have active game session"
                ]);
                return;
            }
            $gameSession=new GameSession();
            $gameSession->save();
            $player=new Player();
            $player->position=0;
            $player->slot=1;
            $player->save();
            $gameSession->link("players",$player);
            $gameSession->link("users",$user);

            $connection->sendEncoded([]);
        });
    }
    public function actionPick()
    {
        $this->startWebSocket("/pick",function($user,$connection,$data){
            $gameSession=GameSession::findOne($user->session_id);
            if($gameSession&&!isset($gameSession[$data->playerSlot])){
                $gameSession[$data->playerSlot]=$user->id;
                $gameSession->save();
                $connection->sendEncoded(
                [
                    "gameSession"=>$gameSession->attributes,
                ]);
                return;
            }
            $connection->sendEncoded([]);
        });
    }
    public function startWebSocket(string $socketAction="",callable $onMessageCallback){
        $worker = new MyWorker($this->socketPath.$socketAction);
        $worker->onMessageDecoded = function (MyTcpConnection $connection, $data) use ($worker,$onMessageCallback) {
            $user=UserSocket::getUserByAuthInfo($data->authInfo);
            var_dump($user->id);
            if ($user) {
                $onMessageCallback($user,$connection,$data,$worker);
            } else {
                var_dump("auth false");
                $connection->sendEncoded([
                    "status code" => 401
                ]);
            }
        };
        MyWorker::runAll();
    }

    public function actionX(){
        // $data=GameSession::find(["id"=>1])
        // ->joinWith(["players"])
        // ->joinWith("players.hero")
        // ->joinWith(["players.user"=>function($query){
        //     $query->select("id,username");
        // }])
        // ->one();
        $data=GameSession::find(["id"=>1])
        ->joinWith(["players"=>function($query){
            $query->orderBy(['slot'=>SORT_ASC]);
        },"players.hero","players.user"=>function($query){
            $query->select(["id","username"]);
        }])
        ->asArray()
        ->one();
        var_dump($data["players"]);
    }
}
