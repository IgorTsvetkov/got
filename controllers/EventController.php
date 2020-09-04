<?php

namespace app\controllers;

use app\models\GameSession;
use Exception;
use yii\web\Controller;

class EventController extends Controller
{
    public function actionRandom($type)
    {
        return $this->redirect("/events/".$type."/random");
    }
    public function actionView($type,$id)
    {
        return $this->redirect(["/events/".$type."/view","id"=>$id]);
    }
    public function actionDo($type,$id)
    {
        return $this->redirect(["/events/".$type."/do","id"=>$id]);
    }
}
