<?php

namespace app\controllers;

use Yii;
use app\models\Player;
use app\helpers\ResponseHelper;

class PlayerController extends \yii\web\Controller
{
    public function actionUpdateHero(int $player_id,int $hero_id)
    {
        $player=Player::find()->where(["player.id"=>$player_id])->limit(1)->joinWith("user")->one();
        if($player->user->id===Yii::$app->user->id)
        {
            $player->hero_id=$hero_id;
            $player->update();
            return ResponseHelper::Success(["status"=>"success"]);
        }
        return $this->asJson(["error"=>["message"=>"Вы не можете выбрать этого персонажа"],"player_id"=>$player->id,"user_id_app"=>Yii::$app->user->id]);
    }

}
