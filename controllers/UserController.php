<?php

namespace app\controllers;

use yii\rest\ActiveController;

class UserController extends ActiveController
{
    public $modelClass="app\models\User";
    protected function verbs() {
        $verbs = parent::verbs();
         $verbs =  [
             'index' => ['GET', 'POST', 'HEAD'],
             'view' => ['GET', 'HEAD'],
             'create' => ['POST'],
             'update' => ['PUT', 'PATCH']
         ];
        return $verbs;
     }
}
