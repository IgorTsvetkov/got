<?php

namespace app\controllers;

use Error;
use app\models\Player;
use app\helpers\ResponseHelper;
use app\helpers\TurnStageHelper;
use app\models\UtilityGameStatus;

class UtilityGameStatusController extends \yii\web\Controller
{
    public function actionView(int $game_session_id, int $utility_id)
    {
        list("player_id" => $owner_player_id) = UtilityGameStatus::find()
            ->select("player_id")
            ->where(["game_session_id" => $game_session_id])
            ->andWhere(["utility_id" => $utility_id])
            ->asArray()
            ->one();
        $count_utility = UtilityGameStatus::find()->where(["player_id" => $owner_player_id])->count();
        $data = [
            "count" => $count_utility,
            "player_id" => $owner_player_id,
        ];
        return ResponseHelper::Success($data);
    }
    public function actionBuy(int $id)
    {
        $player = Player::me()->with("gameSession")->one();
        $game = $player->gameSession;
        $isAlreadyBought = UtilityGameStatus::find()->where(["utility_id" => $id])->andWhere(["game_session_id" => $game->id])->exists();
        if ($isAlreadyBought)
            throw new Error("The коммунальное предприятие has already bought");
        $utilityStatus = new UtilityGameStatus();
        $utilityStatus->utility_id = $id;
        $utilityStatus->player_id = $player->id;
        $utilityStatus->game_session_id = $player->id;
        $utilityStatus->save(false);
        /** @var GameSession */
        $game->turn_stage = TurnStageHelper::FINISHED;
        $game->update(false);
        $data = [
            "game" => [
                "turn_stage" => $game->turn_stage
            ],
            "utility_id" => $id
        ];
        return ResponseHelper::Socket("game", $data);
    }
}
