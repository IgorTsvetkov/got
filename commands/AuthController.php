<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace app\commands;

use Yii;
use app\models\User;
use yii\web\Session;
use Workerman\Worker;
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
class AuthController extends Controller
{
    public function beforeAction($action)
    {
        return parent::beforeAction($action);
    }
    // public function behaviors()
    // {
    //     return[            
    //         TimestampBehavior::classname(),
    //     ];
    // }
    // /**
    //  * This command echoes what you have entered as the message.
    //  * @param string $message the message to be echoed.
    //  * @return int Exit code
    //  */
    // public function actionStart()
    // {
    //     $worker=new Worker("websocket://127.0.0.1:8989");
    //     $worker->count=1;
    //     $worker->onConnect=function($connection){
    //         $connection->send('Connected');
    //     };

    //     $worker->onMessage=function($connection,$data)use($worker){
    //         $user=User::find()->one();
    //         $currentSession=Session::find(["user_id"=>$user->id],)->orderBy(['id'=>SORT_DESC])->one();

    //         foreach($worker->connections as $con){
    //             $con->send("username:".$user->username);
    //         }
    //     };
    //     Worker::runAll();
    //     ExitCode::OK;
    // }
    // public function actionJoinToRoom($id){
    //     $user=User::find()->one();

        
    //     if(Yii::$app->user->hasUnfinishedSession())
    //     {
    //         //Предложить пользователю продолжить предыдущую сессию
    //         return;
    //     }              
    // }
    public function actionCreateRoom($name="default"){
        // User::getUserSocket()s
        // $user=Yii::$app->user->login();
        // echo $user->username;
        // if($user->hasUnfinishedSession())
        // {
        //     //Предложить пользователю продолжить предыдущую сессию
        //     return;
        // }
        // $session=new Session();
        // $session->user_id=$user->id;
        // $session->save();
        // return $this->view("room");
    }
    public function actionTest()
    {
        $worker=new MyWorker("websocket://127.0.0.1:8989");
        $worker->count=1;
        $worker->onConnect=function($connection){
            $connection->send('Connected');
        };
        $worker->onMessage=function($connection,$data)use($worker){            
            if(UserSocket::getUserByUserInfo($data->userInfo)){
                $connection->send(json_encode([
                    "message"=>"yes1",
                    "status code"=>200
                ]));                    
            }
            else{
                $connection->send([
                    "status code"=>401
                    ]); 
            }
        };
        Worker::runAll();
        ExitCode::OK;
    }
}