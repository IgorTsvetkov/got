<?php

namespace app\controllers;

use Yii;
use app\models\User;

class SocketController extends \yii\web\Controller
{
    public function actionIndex()
    {
        return $this->render('index');
    }

    public function actionTest()
    {
        return $this->render('test');
    }
    public function actionAuth()
    {
        $id=Yii::$app->user->getId();
        $user=User::findOne($id);
        $userInfo=[
            "id"=>$user->id,
            "authKey"=>$user->authKey,
            "cookie"=>$_COOKIE
        ];
        return json_encode($userInfo);
        // return json_encode($id);
    }

}
