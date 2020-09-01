<?php

namespace app\controllers;

use Exception;
use yii\web\Controller;

class EventController extends Controller
{
    public function actionGetRandom($event_id)
    {
        switch ($event_id) {
            case '3':
                return $this->redirect("events/spider/getRandom");
                break;
            
            default:
                throw new Exception("Unhandled type of a card event");
                break;
        }
    }
}
