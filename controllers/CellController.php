<?php

namespace app\controllers;

use Yii;
use yii\web\Response;
use yii\rest\ActiveController;

class CellController extends ActiveController
{
    public $modelClass="app\models\Cell";
    public function behaviors(): array
    {
        $behaviors = parent::behaviors();
        $behaviors['corsFilter'] = [
            'class' => \yii\filters\Cors::class,
            'cors' => [
                'Origin' => [Yii::$app->request->getOrigin()],
                'Access-Control-Allow-Credentials' => true,
            ],
        ];
        return $behaviors;
    }
}
