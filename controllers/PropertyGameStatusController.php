<?php

namespace app\controllers;

use Yii;
use app\models\User;
use app\models\Player;
use app\models\Property;
use app\models\GameSession;
use yii\filters\VerbFilter;
use app\helpers\ResponseHelper;
use app\helpers\YesNo;
use app\models\UserGameSession;
use app\models\PropertyGameStatus;
use app\models\RentState;
use Exception;
use yii\helpers\VarDumper;

class PropertyGameStatusController extends \yii\web\Controller
{
    public function behaviors()
    {
        return [
            [
                'class' => VerbFilter::class,
                'actions' => [
                    'view' => ['GET'],
                ]
            ]
        ];
    }
    public function actionCreate($property_id)
    {
        
        /** @var Player */
        $player = Player::find()
            ->orderBy(["id" => SORT_DESC])
            ->where(["user_id" => Yii::$app->user->id])
            ->with("gameSession")
            ->limit(1)
            ->one();
        /** @var PropertyGameStatus */
        $isBought = PropertyGameStatus::find()
            ->where(["property_id" => $property_id])
            ->andWhere(["game_session_id" => $player->game_session_id])
            ->exists();
        if ($isBought)
            return ResponseHelper::Error("уже куплено");
        //это этот игрок?
        if ($player->user->id !== Yii::$app->user->id)
            return ResponseHelper::Error("вы не можете использовать данные другого пользователя");
        $property = Property::find()->where(["id" => $property_id])->with("cell")->limit(1)->one();
        // можно купить только в свой ход и только если игрок стоит на клетке с property
        if (
            $player->id === $player->gameSession->turn_player_id
            &&
            $player->position == $property->cell->position
        ) {
            if($player->canPay($property->cost)===false)
                return ResponseHelper::Error("недостаточно средств"); //TO DO Аукцион
            $player->pay($property->cost);
            $player->update(false);

            $model = new PropertyGameStatus();
            $model->rent_state_id=1;
            $model->property_id = $property_id;
            $model->game_session_id = $player->game_session_id;
            $model->player_id = $player->id;
            $model->save(false);
            /** @var GameSession */
            $game=$player->gameSession;
            $game->is_action_done=YesNo::YES;
            $game->update();
            return ResponseHelper::Socket("property-bought",["is_action_done"=>$game->is_action_done,"player_id"=>$player->id,"money" => $player->money]);
        }
        throw new Exception("в данный момент вы не можете купить это место");
        return ResponseHelper::Error("в данный момент вы не можете купить это место");
    }
    public function actionImprove($property_id)
    {
        $user_id=Yii::$app->user->id;
        $player=Player::find()->where(["user_id"=>$user_id])->orderBy(["id"=>SORT_DESC])->limit(1)->one();
        $propertyGameStatus=PropertyGameStatus::find()
        ->where(["player_id"=>$player->id,"property_id"=>$property_id])
        ->with(["property","gameSession"])
        ->limit(1)
        ->one();
        if($propertyGameStatus->levelUp())
        {
            /** @var Property */
            $property=$propertyGameStatus->property;
            $cost=$property->getAttribute($propertyGameStatus->rentState->name);
            if($player->canPay($cost)){
                $player->pay($cost);
                $player->update();
            }
            $game=$propertyGameStatus->gameSession;
            $game->is_action_done=YesNo::YES;
            $game->update();
            $data=[
                "player_id"=>$player->id,
                "money"=>$player->money,
                "is_action_done"=>$game->is_action_done
                // "propertyGameStatus"=>$propertyGameStatus,
            ];
            return ResponseHelper::Socket("property-improve",$data);
        }
        throw new Exception("Как ты здесь оказался?");
        // return ResponseHelper::Error("Вы больше не можете улучшать ");
    }
}
