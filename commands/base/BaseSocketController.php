<?php

namespace app\commands\base;


use yii\console\Controller;
use app\websockets\MyWorker;
use app\websockets\MyTcpConnection;
use Exception;

require_once __DIR__ . '../../../vendor/autoload.php';

class BaseSocketController extends Controller
{

    public function startWebSocket(string $socketAction, callable $onMessageCallback)
    {
        $ip="websocket://127.0.0.1";
        $socketPathes = [
            "/send-local-to-all" => $ip.":8989",
            "/send-to-all" => $ip.":8990"
        ];
        $socketPath=$socketPathes[$socketAction];
        if(empty($socketPath))
            throw new Exception("There is no socket path with ".$socketAction." name");
        $worker = new MyWorker($socketPath);
        $worker->onMessageDecoded = function (MyTcpConnection $connection, $data) use ($worker, $onMessageCallback) {
            // $user=UserSocket::getUserByAuthInfo($data->authInfo);
            // if ($user) {
            if (isset($data->uid))
                $connection->uid = $data->uid;
            $user = null;
            $onMessageCallback($user, $connection, $data, $worker);
            // } else {
            //     var_dump("auth false");
            //     $connection->sendEncoded([
            //         "status code" => 401
            //     ]);
            // }
        };
        $worker->onWorkerStop = function ($connection) {
            var_dump("worker start");
        };
        $worker->onWorkerStop = function ($connection) {
            var_dump("worker stop");
        };
        $worker->onClose = function ($connection) {
            var_dump("close");
        };
        $worker->onConnect = function ($connection) {
            $connection->maxSendBufferSize = 2 * 1024 * 1024;
            var_dump("connect");
            var_dump($connection->maxSendBufferSize);
        };
        $worker->onBufferFull = function () {
            var_dump("Buffer is full");
        };
        $worker->onError = function () {
            var_dump("server error from handler");
            // MyWorker::reloadAllWorkers();
        };
        MyWorker::runAll();
    }
}
