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
            $user=UserSocket::getUserByAuthInfo($data->authInfo);
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
}
