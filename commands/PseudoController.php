<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace app\commands;

use Yii;
use app\models\UserSocket;
use yii\console\Controller;
use app\websockets\MyWorker;

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
    public function actionSessionStart(){
        // $user_id_array=[1,1234,1234,124,3];
        // $gameSessionConfig=new GameSessionConfig();
        // #model load
        // $gameSessionConfig->name="My game";
        // $gameSessionConfig->playersCount=6;
        // $gameSessionConfig->isPrivate=true;
        // $gameSessionConfig->password="123asd";

        // if(count($user_id_array))
        //     $connection->send("cant start");

    }
    public function actionCellAction()
    {
        $worker=new MyWorker("websocket://127.0.0.1:8989");
        $worker->count=1;
        $worker->onConnect=function($connection){
            $connection->send('Connected');
        };
        $worker->onMessageDecoded=function($connection,$data)use($worker){       
            $user=UserSocket::getUserByAuthInfo($data->authInfo);     
            if($user){
                $session=$user->currentSession;
                $currentTurn=$session->currentTurn;
                if($currentTurn->user_id===$user->id){
                    $cell=Cell::findOne(["position"=>$currentTurn->position]);
                    if($cell.isProperty())
                        $property=SessionProperty::findOne(
                        [
                            "session_id"===$session->id,
                            "property_id"===$cell->property_id
                        ]);
                        if($property->isOwned())
                        {
                            if($property->owner_id===$user->id)
                                $connection->send("want to level up?");
                            else
                                $connection->send("pay money");
                        }
                        else{
                            $connection->send("Want to buy property?");
                        }
                    #
                }
                $connection->send([
                    "message"=>"yes1",
                    "status code"=>200
                ]);                    
            }
            else{
                var_dump("auth success");
                $connection->send([
                    "status code"=>401
                    ]); 
            }
        };
        MyWorker::runAll();
    }
}