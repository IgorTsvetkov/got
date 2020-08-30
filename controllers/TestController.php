<?php

namespace app\controllers;

use Yii;
use app\models\User;
use yii\web\Controller;

class TestController extends Controller
{
    public function actionIndex()
    {
        $user=User::me();
        // $game=$user->getAttributes();
        return $this->render("index",compact("game"));
    }
}
