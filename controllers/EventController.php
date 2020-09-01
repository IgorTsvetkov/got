<?php

namespace app\controllers;

use Exception;
use yii\web\Controller;

class EventController extends Controller
{
    public function actionRandom($type)
    {
        switch ($type) {
            case 'spyder':
                return $this->redirect("events/spider/getRandom");
                break;
            
            default:
                throw new Exception("Unhandled type of a card event");
                break;
        }
    }
}
