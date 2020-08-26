<?php

namespace app\controllers;

use app\models\Hero;
use app\helpers\ResponseHelper;

class HeroController extends \yii\web\Controller
{
    public function actionIndex()
    {
        $heroes=Hero::find()->asArray()->all();
        return ResponseHelper::Success($heroes);
    }

}
