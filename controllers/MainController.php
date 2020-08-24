<?php

namespace app\controllers;

class MainController extends \yii\web\Controller
{
    public function asSocketJson($socketActionName,$data){
        $this->asJson(["action"=>$socketActionName,"data"=>$data]);
    }
}
