<?php

namespace app\controllers;

use app\models\GameSession;

class GotController extends \yii\web\Controller
{
    public function actionIndex()
    {
        $this->layout=false;
        return $this->render('index');
    }
    public function actionCreate(){
        $session=new GameSession();
        return $this->render('create');
    }
}
