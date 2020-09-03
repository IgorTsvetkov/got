<?php

namespace app\controllers;

use Exception;
use yii\web\Controller;

class EventController extends Controller
{
    public function actionRandom($type)
    {
        return $this->redirect("/events/".$type."/random");
    }
    public function actionDo($type,$id)
    {
        return $this->redirect(["/events/".$type."/do","id"=>$id]);
    }
}
