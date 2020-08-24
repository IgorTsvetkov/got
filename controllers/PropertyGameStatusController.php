<?php

namespace app\controllers;

use Yii;
use app\models\User;
use app\models\Player;
use app\models\Property;
use app\models\GameSession;
use yii\filters\VerbFilter;
use app\models\UserGameSession;
use app\models\PropertyGameStatus;

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
        $player = Player::find()
            ->orderBy(["id" => SORT_DESC])
            ->where(["user_id" => Yii::$app->user->id])
            ->with("gameSession")
            ->limit(1)
            ->one();
        $isBought = PropertyGameStatus::find()
            ->where(["property_id" => $property_id])
            ->andWhere(["game_session_id" => $player->game_session_id])
            ->exists();
        if ($isBought)
            return $this->asJson(["error" => ["message" => "уже куплено"]]);
        //это этот игрок?
        if ($player->user->id !== Yii::$app->user->id)
            return $this->asJson(["error" => ["message" => "вы не можете использовать данные другого пользователя"]]);
        $property = Property::find()->where(["id" => $property_id])->with("cell")->limit(1)->one();
        // можно купить только в свой ход и только если игрок стоит на клетке с property
        if (
            $player->id === $player->gameSession->turn_player_id
            &&
            $player->position == $property->cell->position
        ) {
            if ($player->money < $property->cost)
                return $this->asJson(["error" => ["message" => "недостаточно средств"]]); //TO DO Аукцион
            $player->money -= $property->cost;
            $player->update(false);

            $model = new PropertyGameStatus();
            $model->property_id = $property_id;
            $model->game_session_id = $player->game_session_id;
            $model->player_id = $player->id;
            $model->save(false);
            return $this->asJson(["success" => true, "data" => ["player_id"=>$player->id,"money" => $player->money]]);
        }
        return $this->asJson(["error" => ["message" => "в данный момент вы не можете купить это место"]]);
    }
}
