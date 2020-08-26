<?php

namespace app\controllers;

use Yii;
use yii\rest\ActiveController;
use yii\web\Controller;
use yii\web\Response;

class UtilityController extends Controller
{
    public function actionTest()
    {
        $response=Yii::$app->response;
        $response->format=Response::FORMAT_JSON;
        
        $response->data=Yii::$app->user->identity;

        return $response;
    }
}
