<?php

namespace app\controllers\events;

use app\helpers\ResponseHelper;
use app\models\User;
use app\models\Player;
use app\helpers\SpyderEventHelper;
use app\models\Property;
use Exception;
use GuzzleHttp\Psr7\Response;

class SpyderController extends \yii\web\Controller
{
    public function actionGetRandom()
    {
        $player=Player::me();
        $event=EventSpyder::getRandom();
        switch ($event->operation) {
            case 'earn':
                $player->earn($event->money);
                break;
            case 'pay':
                if($player->canPay($event->money))
                    $player->pay($event->money);
                break;
            case 'payForRepairHomeAndInn':
                $damageCost=SpyderEventHelper::calculateWildfireDamageCost($player->id);
                if($player->canPay($damageCost))
                    $player->pay($damageCost);
                break;
            case 'teleportKingLanding':
                $player->teleportToProperty("king's landing");
                break;
            case 'rollAndPayMultiply10':
                $rollCount=null;
                $isDiceRolled=null;
                $multiplier=10;
                // if(!$isDiceRolled){
                    // return ResponseHelper::Socket()
                    $cost=$rollCount*$multiplier;
                    $player->pay($cost);
                break;
            case 'payPrevious':
                $previousTurnPlayer=$player->previousTurnPlayer;
                $player->pay($event->money);
                $previousTurnPlayer->earn($event->money);
            break;
            // case 'earnIfPassStart':

            default:
               throw new Exception("There is no SpyderEvent with name".$event->operation);
                break; # 
        }
    }
    public function takeMoney(){
        
    }
}
