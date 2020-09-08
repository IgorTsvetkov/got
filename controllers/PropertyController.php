<?php

namespace app\controllers;

use Yii;
use yii\web\Controller;
use yii\filters\VerbFilter;
use app\helpers\ResponseHelper;
use app\models\estate\Property;
use app\models\GameSession;
use app\models\UserGameSession;

class PropertyController extends Controller
{
    public function behaviors()
    {
        return [
            [
                'class' => VerbFilter::class,
                'actions' => [
                    'view' => ['GET']
                ]
            ]
        ];
    }
    public function actionView($id)
    {
        $game=GameSession::me()->one();
        $game_session_id = $game->id;
        $property = Property::find()
            ->where(["id" => $id])
            ->with(["propertyGameStatuses" => function ($query) use ($game_session_id) {
                $query->where(["game_session_id" => $game_session_id]);
                $query->with(["player.user" => function ($query) {
                    $query->select("username");
                }, "player.hero", "rentState"]);
            }])
            ->limit(1)
            ->asArray()
            ->one();
        $property["propertyGameStatus"] = $property["propertyGameStatuses"][0];
        unset($property["propertyGameStatuses"]);
        return ResponseHelper::Success($property);
    }
}
