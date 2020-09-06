<?php

namespace app\controllers;

use app\helpers\ResponseHelper;
use app\helpers\TurnStageHelper;
use app\models\GameSession;
use app\models\Player;
use app\models\Tax;
use app\models\TaxGameStatus;
use Error;
use GuzzleHttp\Psr7\Response;
use yii\db\Query;

class TaxGameStatusController extends \yii\web\Controller
{
    public function actionView(int $game_session_id, int $tax_id)
    {

        list("player_id" => $owner_player_id) = TaxGameStatus::find()
            ->select("player_id")
            ->where(["game_session_id" => $game_session_id])
            ->andWhere(["tax_id" => $tax_id])
            ->asArray()
            ->one();
        $count_taxes = TaxGameStatus::find()->where(["player_id" => $owner_player_id])->count();
        $data = [
            "count" => $count_taxes,
            "player_id" => $owner_player_id,
        ];
        return ResponseHelper::Success($data);
    }
    public function actionBuy(int $id)
    {
        $player = Player::me()->with("gameSession")->one();
        $game = $player->gameSession;
        $isAlreadyBought = TaxGameStatus::find()->where(["tax_id" => $id])->andWhere(["game_session_id"=>$game->id])->exists();
        if ($isAlreadyBought)
            throw new Error("The house has already bought");
        $taxStatus = new TaxGameStatus();
        $taxStatus->player_id = $player->id;
        $taxStatus->tax_id = $id;
        $taxStatus->game_session_id = $player->id;
        $taxStatus->save(false);
        /** @var GameSession */
        $game->turn_stage = TurnStageHelper::FINISHED;
        $game->update(false);
        $data = [
            "game" => [
                "turn_stage" => $game->turn_stage
            ],
            "tax_id"=>$id
        ];
        return ResponseHelper::Socket("game", $data);
    }
}
