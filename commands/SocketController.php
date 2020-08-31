<?php

/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace app\commands;

use app\commands\base\BaseSocketController;


/**
 * This command echoes the first argument that you have entered.
 *
 * This command is provided as an example for you to learn how to create console commands.
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class SocketController extends BaseSocketController
{  
    public function actionGame(){
        $this->startWebSocket("/send-local-to-all",function($user,$currentConnection,$data,$worker){   
            foreach ($worker->connections as $connection) {
                if(isset($data)&&isset($data->uid)&&$connection->uid==$data->uid){
                    echo "uid".$connection->uid."\n";
                    $connection->sendEncoded($data);
                }
            }
        });
    }
    public function actionAll(){
        $this->startWebSocket("/send-to-all",function($user,$currentConnection,$data,$worker){   
            echo "send-to-all";
            foreach ($worker->connections as $connection) {
                    $connection->sendEncoded($data);
            }
        });
    }
}
