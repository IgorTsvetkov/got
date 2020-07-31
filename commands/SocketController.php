<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace app\commands;

use Yii;
use yii\console\ExitCode;
use app\models\UserSocket;
use yii\console\Controller;
use app\websockets\MyWorker;
use yii\behaviors\TimestampBehavior;

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
    public function actionTest()
    {
        $worker=new MyWorker("websocket://127.0.0.1:8989");
        $worker->count=1;
        $worker->onConnect=function($connection){
            $connection->send('Connected');
        };
        $worker->onMessageDecoded=function($connection,$data)use($worker){            
            if(UserSocket::getUserByUserInfo($data->userInfo)){
                $connection->onMessageEncoded([
                    "message"=>"yes1",
                    "status code"=>200
                ]);                    
            }
            else{
                $connection->send([
                    "status code"=>401
                    ]); 
            }
        };
        MyWorker::runAll();
    }
}