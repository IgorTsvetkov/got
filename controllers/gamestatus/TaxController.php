<?php

namespace app\controllers\gamestatus;

use Error;
use Exception;
use yii\db\Query;
use app\models\Tax;
use app\models\Cell;
use app\models\Player;
use app\models\GameSession;
use app\models\TaxGameStatus;
use GuzzleHttp\Psr7\Response;
use app\helpers\ResponseHelper;
use app\helpers\TurnStageHelper;
use app\models\gamestatus\CommonGameStatus;

class TaxController extends \yii\web\Controller
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
        $count_taxes = CommonGameStatus::find()
        ->where(["player_id" => $owner_player_id])
        ->andWhere(["estate_type_id"=>CommonGameStatus::TYPE_ESTATE_TAX])
        ->count();
        
        $data = [
            "count" => $count_taxes,
            "player_id" => $owner_player_id,
        ];
        return ResponseHelper::Success($data);
    }
    // public function actionBuy(int $id,$is_auction=false)
    // {
    //     $player = Player::me()->with("gameSession")->one();
    //     /** @var GameSession */
    //     $game = $player->gameSession;
    //     $tax=Tax::find()->where(["id"=>$id])->with("cell")->one();
    //     /** @var Cell */
    //     $cell=$tax->cell;
    //     if(!$game->isTurn($player->id)&&!$cell->hasEqualPosition($player))
    //         throw new Exception("Access denied");
            
    //     $isAlreadyBought = TaxGameStatus::find()
    //     ->where(["tax_id" => $id])
    //     ->andWhere(["game_session_id"=>$game->id])
    //     ->exists();
    //     if ($isAlreadyBought)
    //         throw new Error("The house has already bought");
    //     $model = new TaxGameStatus();
    //     $model->cell_id=$cell->id;
    //     $model->player_id = $player->id;
    //     $model->tax_id = $id;
    //     $model->game_session_id = $player->id;
    //     $model->save(false);
    //     /** @var GameSession */
    //     $game->turn_stage = TurnStageHelper::FINISHED;
    //     $game->update(false);
    //     $data = [
    //         "game" => [
    //             "turn_stage" => $game->turn_stage
    //         ],
    //         "player"=>,
    //         "tax_id"=>$id,
    //     ];
    //     return ResponseHelper::Socket("game", $data);
    // }
}
