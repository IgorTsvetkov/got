<?php

/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace app\commands;
use app\models\GameSession;
use app\models\Player;
use yii\console\ExitCode;
use app\models\UserSocket;
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
    public function actionStart(){
        $worker = new MyWorker("websocket://127.0.0.1:8989/start");
        $worker->count = 1;
        $worker->onMessageDecoded = function (MyTcpConnection $connection, $data) use ($worker) {
            $user=UserSocket::getUserByAuthInfo($data->authInfo);
            var_dump($user->id);
            if ($user) {
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
