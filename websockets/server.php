<?php
namespace websockets\server;

use Workerman\Worker;
require_once __DIR__ .'/../vendor/autoload.php';

$worker=new Worker("websocket://127.0.0.1:8989");
$worker->count=1;
$worker->onConnect=function($connection){
    $connection->send('Connected');
};

$worker->onMessage=function($connection,$data)use($worker){
    foreach($worker->connections as $con){
        $con->send($data);
    }
};

// $worker->onClose=function($connection){
//     $connection->send('Closed');
// };
Worker::runAll();