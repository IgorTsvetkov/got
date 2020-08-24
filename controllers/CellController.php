<?php

namespace app\controllers;

use Yii;
use app\models\Cell;
use Error;
use yii\web\Controller;


class CellController extends Controller
{
    public function actionIndex($json=true)
    {
        if($json){
            $cells=Cell::find()->joinWith(explode(",","property.group,tax,utility,event"))->asArray()->all();
            return $this->asJson($cells);
        }
        throw new Error("unhundled action behavior");
    }
}
