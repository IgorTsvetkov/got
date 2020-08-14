<?php

namespace app\controllers;

use yii\filters\AccessControl;

class TestController extends \yii\web\Controller
{
    public function actionIndex()
    {
        return $this->render('index');
    }
}
