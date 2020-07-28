<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace app\commands;

use Yii;
use app\models\User;
use Workerman\Worker;
use yii\console\ExitCode;
use yii\console\Controller;
require_once __DIR__ .'/../vendor/autoload.php';
/**
 * This command echoes the first argument that you have entered.
 *
 * This command is provided as an example for you to learn how to create console commands.
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class SocketController extends Controller
{
    /**
     * This command echoes what you have entered as the message.
     * @param string $message the message to be echoed.
     * @return int Exit code
     */
    public function actionStart()
    {
        $worker=new Worker("websocket://127.0.0.1:8989");
        $worker->count=1;
        $worker->onConnect=function($connection){
            $connection->send('Connected');
        };

        $worker->onMessage=function($connection,$data)use($worker){
            $user=User::find()->one();
            foreach($worker->connections as $con){
                $con->send("username:".$user->username);
            }
        };
        Worker::runAll();
        ExitCode::OK;
    }
    public function actionTest()
    {
        $user=User::find()->one();
        echo "username:".$user->username;
        ExitCode::OK;

    }
}