<?php

namespace app\controllers;

use Yii;
use yii\web\Controller;
use app\models\Property;
use yii\filters\VerbFilter;
use app\models\UserGameSession;

class PropertyController extends Controller
{
    public function behaviors()
    {
        return [
            [
                'class'=>VerbFilter::class,
                'actions'=>[
                    'view'=>['GET']
                ]
            ]
                ];
    }
    public function actionView($id)
    {
        $user_game_session=UserGameSession::find()
        ->select("game_session_id")
        ->orderBy(["game_session_id"=>SORT_DESC])
        ->where(["user_id"=>Yii::$app->user->id])
        ->limit(1)
        ->one();
        $game_session_id=$user_game_session->game_session_id;
        $property=Property::find()
        ->where(["id"=>$id])
        ->with(["propertyGameStatuses"=>function($query)use($game_session_id){
            $query->where(["game_session_id"=>$game_session_id])->limit(1);
            $query->with(["player.user"=>function($query){
                $query->select("username");
            },"player.hero","rentState"]);
        }])
        ->limit(1)
        ->asArray()
        ->one();
        $property["propertyGameStatus"]=$property["propertyGameStatuses"][0];
        unset($property["propertyGameStatuses"]);
        return $this->asJson($property);
    }
}
