<?php

namespace app\controllers;

use app\models\Player;

class GotController extends \yii\web\Controller
{
    public function actionGame()
    {
        $this->layout=false;
        return $this->render('index');
    }
    public function actionMove($player_id){
        $player=Player::findOne($player_id);
        $player->position+=1;
        $player->update();
        return $this->asJson(["position"=>$player->position]);
    }
}
