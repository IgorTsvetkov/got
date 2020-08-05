<?php

namespace app\controllers;

use Yii;
use yii\web\Response;
use yii\rest\ActiveController;
use yii\data\ActiveDataProvider;

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
    public function actions()
    {
        $actions['index'] = [

            'class' => 'yii\rest\IndexAction',
        
            'modelClass' => $this->modelClass,
        
            'prepareDataProvider' => function () {
        
                return new ActiveDataProvider([
        
                    'query' => $this->modelClass::find(),
        
                    'pagination' => false,
        
                ]);
        
            },
        
        ];
        return $actions;
    }
}
