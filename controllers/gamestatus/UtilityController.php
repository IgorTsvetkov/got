<?php

namespace app\controllers;

use Error;
use Exception;
use app\models\Player;
use app\models\Utility;
use app\helpers\ResponseHelper;
use app\helpers\TurnStageHelper;
use app\models\UtilityGameStatus;
use app\models\gamestatus\CommonGameStatus;

class UtilityGameStatusController extends \yii\web\Controller
{
    public function actionView(int $id,int $game_session_id)
    {
        list("player_id" => $owner_player_id) = CommonGameStatus::find()
            ->select("player_id")
            ->where(["game_session_id" => $game_session_id])
            ->andWhere(["estate_id" => $id])
            ->andWhere(["estate_type_id" => CommonGameStatus::TYPE_ESTATE_TAX])
            ->asArray()
            ->one();
        $count_utilties = CommonGameStatus::find()
            ->where(["player_id" => $owner_player_id])
            ->andWhere(["estate_type_id" => CommonGameStatus::TYPE_ESTATE_TAX]);
        $data = [
            "count" => $count_utilties,
            "player_id" => $owner_player_id,
        ];
        return ResponseHelper::Success($data);
    }
    // public function actionBuy(int $id)
    // {
    //     $player = Player::me()->with("gameSession")->one();
    //     $game = $player->gameSession;
    //     $utility=Utility::find()->where(["id"=>$id])->with("cell")->one();
    //     /** @var Cell */
    //     $cell=$utility->cell;
    //     if(!$game->isTurn($player->id)&&!$cell->hasEqualPosition($player))
    //         throw new Exception("Access denied");
    //     $isAlreadyBought = UtilityGameStatus::find()->where(["utility_id" => $id])->andWhere(["game_session_id" => $game->id])->exists();
    //     if ($isAlreadyBought)
    //         throw new Error("The коммунальное предприятие has already bought");
    //     $model = new UtilityGameStatus();
    //     $model->cell_id=$cell->id;
    //     $model->utility_id = $id;
    //     $model->player_id = $player->id;
    //     $model->game_session_id = $player->id;
    //     $model->save(false);
    //     /** @var GameSession */
    //     $game->turn_stage = TurnStageHelper::FINISHED;
    //     $game->update(false);
    //     $data = [
    //         "game" => [
    //             "turn_stage" => $game->turn_stage
    //         ],
    //         "utility_id" => $id
    //     ];
    //     return ResponseHelper::Socket("game", $data);
    // }
}
