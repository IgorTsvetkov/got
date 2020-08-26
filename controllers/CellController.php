<?php

namespace app\controllers;

use Yii;
use Error;
use app\models\Cell;
use yii\web\Controller;
use app\helpers\ResponseHelper;


class CellController extends Controller
{
    public function actionIndex($json=true)
    {
        if($json){
            $cells=Cell::find()->joinWith(explode(",","property.group,tax,utility,event"))->asArray()->all();
            return ResponseHelper::Success($cells);
        }
        throw new Error("unhundled action behavior");
    }
}
