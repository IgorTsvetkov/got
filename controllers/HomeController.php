<?php

namespace app\controllers;

use Yii;

class HomeController extends \yii\web\Controller
{
    public function actionIndex()
    {
        return $this->render('index');
    }
    public function actionStartGameSession()
    {
        // if (Yii::$app->user->isGuest) {
        //     return $this->redirect("/site/login");
        // }
        // $session=new GameSession();
        // $session->user1_id=Yii::$app->user->id;
        // $session->user2_id=100;
        // $session->save();
    }
}
