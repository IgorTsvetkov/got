<?php

/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace app\commands;

use app\models\User;
use app\models\Player;
use yii\console\ExitCode;
use app\models\UserSocket;
use app\models\GameSession;
use yii\console\Controller;
use app\websockets\MyWorker;
use app\websockets\MyTcpConnection;
use app\websockets\MyUdpConnection;


require_once __DIR__ . '/../vendor/autoload.php';
/**
 * This command echoes the first argument that you have entered.
 *
 * This command is provided as an example for you to learn how to create console commands.
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class MovementController extends Controller
{
    public function beforeAction($action)
    {
        return parent::beforeAction($action);
    }
    public function actionTest()
    {   
        $socketConfig = require __DIR__ . '/../config/socket.php';

        $worker = new MyWorker($socketConfig['ip']."/move");
        $worker->count = 1;
        // $worker->onConnect = function ($connection) {
        //     $connection->send('Connected');
        // };
        $position=0;
        $worker->onMessageDecoded = function (MyTcpConnection $connection, $data) use ($position) {
            $user=UserSocket::getUserByAuthInfo($data->authInfo);
            var_dump($user->id);
            if ($user) {
                $player=Player::findOne(['user_id'=>$user->id]);
                if(!$player){
                    $player=new Player();
                    $player->session_id=1;
                    $player->user_id=$user->id;
                    $player->position=0;
                    $player->save();
                }
                $player->position+=1;
                $player->save(false);

                $connection->sendEncoded([
                    "position" => $player->position,
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
    public function actionDo(){

        $user=User::findOne(4);
        $players=$user->players;
        var_dump($players);
    }
}
