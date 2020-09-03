<?php

namespace app\controllers;

use Yii;

class TestController extends \yii\web\Controller
{
    public function actionIndex()
    {
        return $this->render('index');
    }
}
