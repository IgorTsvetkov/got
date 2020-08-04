<?php

namespace app\controllers;

class GotController extends \yii\web\Controller
{
    public function actionIndex()
    {
        $this->layout=false;
        return $this->render('index');
    }

}
