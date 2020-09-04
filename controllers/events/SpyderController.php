<?php

namespace app\controllers\events;

use Exception;
use app\models\User;
use app\models\Player;
use app\models\Property;
use app\models\EventSpyder;
use app\models\GameSession;
use GuzzleHttp\Psr7\Response;
use app\helpers\ResponseHelper;
use app\helpers\SpyderEventHelper;
use app\helpers\TurnStageHelper;

class SpyderController extends \yii\web\Controller
{
    public function actionView($id)
    {
        $event = EventSpyder::findOne($id);
        return ResponseHelper::Success($event);
    }
    public function actionRandom()
    {
        $event = EventSpyder::findOne(5);
        $game=GameSession::meOne();
        $game->current_event_id=$event->id;
        if('rollAndPayMultiply10'){
            $game->turn_stage=TurnStageHelper::ROLL_AGAIN;
            $data=["game"=>$game,"event"=>$event];
            return ResponseHelper::Socket("game",$data); 
        }
        $game->update(false);
        return ResponseHelper::Success($event);
    }
    public function actionDo($id)
    {
        $event = EventSpyder::findOne($id);
        $player = Player::me()->with("gameSession")->one();
        /** @var GameSession */
        $game = $player->gameSession;
        switch ($event->operation) {
            case 'earn':
                $player->earn($event->money);
                $game->turn_stage = TurnStageHelper::FINISHED;
                break;
            case 'pay':
                if ($player->canPay($event->money))
                    $player->pay($event->money);
                break;
            case 'payForRepairHomeAndInn':
                $damageCost = SpyderEventHelper::calculateWildfireDamageCost($player->id);
                if ($player->canPay($damageCost))
                    $player->pay($damageCost);
                break;
            case 'teleportKingLanding':
                $player->teleportToProperty("king's landing");
                break;
            case 'rollAndPayMultiply10':
                if(!$game->turn_stage==TurnStageHelper::ROLL_AGAIN){
                    $game->turn_stage=TurnStageHelper::ROLL_AGAIN; 
                    break;
                }
                $rollCount=$game->rollCount;
                $multiplier = 10;
                $cost = $rollCount * $multiplier;
                $player->pay($cost);
                break;
            case 'payPrevious':
                $previousTurnPlayer = $player->previousTurnPlayer;
                $player->pay($event->money);
                $previousTurnPlayer->earn($event->money);
                break;
                // case 'earnIfPassStart':

            default:
                throw new Exception("There is no SpyderEvent with name" . $event->operation);
                break; # 
        }
        $game->update(false);
        $data = ["game" => $game, "players" => [$player]];
        ResponseHelper::Socket("players-and-game", $data);
    }
}
