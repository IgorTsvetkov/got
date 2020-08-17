<?php

namespace app\controllers;

use app\models\Hero;

class HeroController extends \yii\web\Controller
{
    public function actionIndex()
    {
        $heroes=Hero::find()->asArray()->all();
        return $this->asJson($heroes);
    }

}
