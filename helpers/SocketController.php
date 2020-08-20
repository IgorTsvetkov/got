<?php
namespace app\helpers;


use app\models\UserSocket;
use yii\console\Controller;
use app\websockets\MyWorker;
use app\websockets\MyTcpConnection;
require_once __DIR__ . '/../vendor/autoload.php';

class SocketController extends Controller
{
    public $socketPath="websocket://127.0.0.1:8989";
    
    public function startWebSocket(string $socketAction="",callable $onMessageCallback){
        $worker = new MyWorker($this->socketPath.$socketAction);
        $worker->onMessageDecoded = function (MyTcpConnection $connection, $data) use ($worker,$onMessageCallback) {
            // $user=UserSocket::getUserByAuthInfo($data->authInfo);
            // if ($user) {
                if(empty($connection->uid)&&isset($data->uid))
                    $connection->uid=$data->uid;
                $user=null;
                $onMessageCallback($user,$connection,$data,$worker);
            // } else {
            //     var_dump("auth false");
            //     $connection->sendEncoded([
            //         "status code" => 401
            //     ]);
            // }
        };
        $worker->onWorkerStop=function($connection){
            var_dump("worker start");
        };
        $worker->onWorkerStop=function($connection){
            var_dump("worker stop");
        };
        $worker->onClose=function($connection){
            var_dump("close");
        };
        $worker->onConnect = function ($connection) {
            $connection->maxSendBufferSize=2*1024*1024;
            var_dump("connect");
            var_dump($connection->maxSendBufferSize);
        };
        $worker->onBufferFull=function(){
            var_dump("Buffer is full");
        };
        $worker->onError = function(){
            var_dump("server error from handler");
            // MyWorker::reloadAllWorkers();
        };
        MyWorker::runAll();
    }
}
