<?php

namespace app\controllers;

use Yii;
use Exception;
use app\models\User;
use app\models\Player;
use app\models\Property;
use app\models\GameSession;
use app\helpers\ResponseHelper;
use app\helpers\YesNo;
use app\models\PropertyGameStatus;

class PlayerController extends \yii\web\Controller
{
    public function actionUpdateHero(int $player_id, int $hero_id)
    {
        $player = Player::find()->where(["player.id" => $player_id])->limit(1)->joinWith("user")->one();
        if ($player->user->id === Yii::$app->user->id) {
            $player->hero_id = $hero_id;
            $player->update();
            return ResponseHelper::Success(["status" => "success"]);
        }
        return $this->asJson(["error" => ["message" => "Вы не можете выбрать этого персонажа"], "player_id" => $player->id, "user_id_app" => Yii::$app->user->id]);
    }
    public function actionPayRent(int $player_from_id, int $player_to_id, int $property_id)
    {
        $user = User::me();
        $player_from = Player::findOne($player_from_id);
        $player_to = Player::findOne($player_to_id);
        /** @var PropertyGameStatus */
        $propertyGameStatus = PropertyGameStatus::find()
            ->where(["property_id" => $property_id, "player_id" => $player_to_id])
            ->with(["property.cell", "rentState", "gameSession"])
            ->limit(1)
            ->one();
        //Проверка против злоумышленников
        if (
            $user->id !== $player_from->user_id &&
            empty($propertyGameStatus) &&
            $propertyGameStatus->property->cell->position !== $player_from->position
        )
            throw new Exception("This user has no such player");
        $rent_state_name = $propertyGameStatus->rentState->name;
        /** @var Property */
        $property = $propertyGameStatus->property;
        $rent_cost = $property->getAttribute($rent_state_name);
        if (!$player_from->canPay($rent_cost))
            throw new Exception("have no money");
        $player_from->payTo($player_to, $rent_cost);

        /** @var GameSession */
        $game=$propertyGameStatus->gameSession;
        $game->is_action_done=YesNo::YES;
        $game->update(false);

        $players = [$player_from->getAttributes(), $player_to->getAttributes()];
        $data = [
            "players" => $players,
            "game" => $propertyGameStatus->gameSession->getAttributes(),
            "player_to_id"=>$player_to->id,
            "cost"=>$rent_cost
        ];
        return ResponseHelper::Socket("property-pay-rent", $data);
    }
}
