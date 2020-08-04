<?php

namespace app\controllers;

use yii\web\Response;
use yii\rest\ActiveController;

class CellController extends ActiveController
{
    public $modelClass="app\models\Cell";
    public $enableCsrfValidation = false;

    public function behaviors() {
        $behaviors = parent::behaviors();
    
        // remove authentication filter necessary because we need to 
        // add CORS filter and it should be added after the CORS
        unset($behaviors['authenticator']);
    
        // add CORS filter
        $behaviors['corsFilter'] = [
            'class' => '\yii\filters\Cors',
            'cors' => [
                'Origin' => ['*'],
                'Access-Control-Request-Method' => ['GET', 'POST', 'PUT', 'PATCH', 'DELETE', 'HEAD', 'OPTIONS'],
                'Access-Control-Request-Headers' => ['*'],
            ],
        ];
    
        // re-add authentication filter of your choce
        $behaviors['authenticator'] = [
            'class' => yii\filters\auth\HttpBasicAuth::class
        ];
    
        // avoid authentication on CORS-pre-flight requests (HTTP OPTIONS method)
        $behaviors['authenticator']['except'] = ['options'];
        return $behaviors;
    }
    public function beforeAction($action)
{
    if (parent::beforeAction($action)) {
        \Yii::$app->response->format = Response::FORMAT_JSON;
        return true;
    }

}
}
