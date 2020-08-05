<?php

/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace app\commands;

use app\models\GameSession;
use yii\console\ExitCode;
use app\models\UserSocket;
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
        $worker = new MyWorker("websocket://127.0.0.1:8989");
        $worker->count = 1;
        $worker->onConnect = function ($connection) {
            $connection->send('Connected');
        };
        $worker->onMessageDecoded = function ($connection, $data) use ($worker) {
            $user=UserSocket::getUserByAuthInfo($data->authInfo);
            if ($user) {
                #if auth success do next
                $gameSession=new GameSession();
                $gameSession->user_id=$user->id;



                $connection->send([
                    "message" => "yes1",
                    "status code" => 200
                ]);
            } else {
                var_dump("auth success");
                $connection->send([
                    "status code" => 401
                ]);
            }
        };
        MyWorker::runAll();
    }
}
