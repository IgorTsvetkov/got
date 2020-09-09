<?php

namespace app\controllers\gamestatus;

use Yii;
use Exception;
use app\models\Cell;
use app\models\User;
use app\helpers\YesNo;
use app\models\Player;
use app\models\RentState;
use yii\helpers\VarDumper;
use app\models\GameSession;
use yii\filters\VerbFilter;
use app\models\PropertyGroup;
use app\helpers\ResponseHelper;
use app\models\estate\Property;
use app\models\UserGameSession;
use app\helpers\TurnStageHelper;
use app\models\gamestatus\CommonGameStatus;
use app\models\gamestatus\PropertyGameStatus;

class PropertyController extends \yii\web\Controller
{
    public function behaviors()
    {
        return [
            [
                'class' => VerbFilter::class,
                'actions' => [
                    'view' => ['GET'],
                ]
            ]
        ];
    }
    // public function actionBuy($id,$is_auction=false)
    // {

    //     /** @var Player */
    //     $player = Player::me()->with("gameSession")->one();
    //     /** @var PropertyGameStatus */
    //     $isBought = CommonGameStatus::find()
    //         ->where(["property_id" => $id])
    //         ->andWhere(["game_session_id" => $player->game_session_id])
    //         ->exists();
    //     if ($isBought)
    //         return ResponseHelper::Error("уже куплено");
    //     //это этот игрок?
    //     if ($player->user->id !== Yii::$app->user->id)
    //         return ResponseHelper::Error("вы не можете использовать данные другого пользователя");
    //     /** @var Property */
    //     $property = Property::find()->where(["id" => $id])->with(["cell", "group"])->limit(1)->one();
    //     /** @var GameSession */
    //     $game=$player->gameSession;
    //     /** @var Cell */
    //     $cell=$property->cell;
    //     // защита от злоумышленников
    //     // можно купить только в свой ход и только если игрок стоит на клетке с property
    //     if (!$is_auction&&$player->in_auction===YesNo::NO
    //         ||!$game->isTurn($player->id) && !$cell->hasEqualPosition($player)) {
    //         throw new Exception("в данный момент вы не можете купить это место");
    //     }
    //     if ($player->canPay($property->cost) === false)
    //         return ResponseHelper::Error("недостаточно средств"); //TO DO Аукцион
    //     if($is_auction){
    //         $player->in_auction=YesNo::NO;
    //     }
    //     $player->pay($property->cost);

    //     $model = new PropertyGameStatus();
    //     $model->rent_state_id = 1;
    //     $model->property_id = $id;
    //     $model->game_session_id = $player->game_session_id;
    //     $model->player_id = $player->id;
    //     $model->group_id = $property->group_id;
    //     $model->cell_id=$cell->id;
    //     $model->save(false);

    //     $group_id = $property->group_id;
    //     $game_session_id = $player->game_session_id;
    //     $isMonopoly = PropertyGameStatus::isMonopoly($group_id, $game_session_id);
    //     if ($isMonopoly)
    //         PropertyGameStatus::markGroupImprovable($group_id);

    //     $game->turn_stage = TurnStageHelper::FINISHED;
    //     $game->update();
    //     $data = [
    //         "player" => $player,
    //         "game" => $game,
    //         "property" => ["name" => $property->name, "color" => $property->group->color_name],
    //     ];
    //     return ResponseHelper::Socket("property-bought", $data);
    // }
    public function actionImprove($id)
    {
        $user_id = Yii::$app->user->id;
        $player = Player::find()->where(["user_id" => $user_id])->orderBy(["id" => SORT_DESC])->limit(1)->one();
        $propertyGameStatus = PropertyGameStatus::find()
            ->where(["player_id" => $player->id, "estate_id" => $id,"estate_type_id"=>CommonGameStatus::TYPE_ESTATE_PROPERTY])
            ->with(["property" => function ($q) {
                $q->with("group");
            }, "gameSession"])
            ->limit(1)
            ->one();
            
        if ($propertyGameStatus->levelUp()) {
            /** @var Property */
            $property = $propertyGameStatus->property;
            $cost = $property->getAttribute($propertyGameStatus->rentState->name);
            if ($player->canPay($cost)) {
                $player->pay($cost);
                $player->update();
            }
            $game = $propertyGameStatus->gameSession;
            $game->turn_stage = TurnStageHelper::FINISHED;
            $game->update();
            /** @var Property */
            $property = $propertyGameStatus->property;
            $data = [
                "player" => $player,
                "game" => $game,
                "property" => ["name" => $property->name, "color" => $property->group->color_name],
            ];
            return ResponseHelper::Socket("my-player-and-game", $data);
        }
        throw new Exception("Как ты здесь оказался?");
        // return ResponseHelper::Error("Вы больше не можете улучшать ");
    }
}
