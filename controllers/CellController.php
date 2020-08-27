<?php

namespace app\controllers;

use Yii;
use Error;
use app\models\Cell;
use yii\web\Controller;
use app\helpers\ResponseHelper;
use app\models\PropertyGameStatus;
use yii\db\Query;

class CellController extends Controller
{
    public function actionIndex($game_id,$json=true)
    {
        if($json){
            // $propertyQuery=function($q)use($game_id){
            //     $q->with(["propertyGameStatuses"=>function(Query $q)use($game_id){
            //         $q->where(["game_session_id"=>$game_id]);
            //         $q->with(["player.hero"]);
            //     }]);
            // };
            $playerOwnerQuery=function(Query $q){
                $q->with("playerOwner.hero");
            };
            $cells=Cell::find()->joinWith(["property.group","tax","utility","event","property"=>$playerOwnerQuery])
            ->asArray()
            ->all();
            return ResponseHelper::Success($cells);
        }
        throw new Error("unhundled action behavior");
    }
}
