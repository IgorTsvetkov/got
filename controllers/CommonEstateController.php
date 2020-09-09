<?php

namespace app\controllers;

use Yii;
use Exception;
use app\models\User;
use app\helpers\YesNo;
use app\models\Player;
use app\models\estate\Tax;
use app\models\GameSession;
use yii\filters\VerbFilter;
use app\models\estate\Utility;
use app\helpers\ResponseHelper;
use app\models\estate\Property;
use app\helpers\TurnStageHelper;
use app\models\gamestatus\CommonGameStatus;
use app\models\gamestatus\PropertyGameStatus;

class CommonEstateController extends \yii\web\Controller
{
    public function behaviors()
    {
        return [
            [
                'class' => VerbFilter::class,
                'actions' => [
                    'view' => ['GET'],
                    // 'buy'  => ['POST']
                ]
            ]
        ];
    }
    public function actionBuy(int $type_id,int $id,$is_auction=false)
    {
        /** @var Player */
        $player = Player::me()->with("gameSession")->one();
        /** @var CommonGameStatus */
        $isBought = CommonGameStatus::exist($type_id,$id,$player->game_session_id);
        if ($isBought)
            return ResponseHelper::Error("уже куплено");
        /** @var GameSession */
        $game=$player->gameSession;
        //это этот игрок?
        if ($player->user->id !== Yii::$app->user->id)
            return ResponseHelper::Error("вы не можете использовать данные другого пользователя");
        $statusModel=null;
        switch ($type_id) {
            case CommonGameStatus::TYPE_ESTATE_PROPERTY:
                $statusModel=Property::find()->where(["id" => $id])->with(["cell", "group"])->one();
                break;
            case CommonGameStatus::TYPE_ESTATE_TAX:
                $statusModel=Tax::find()->where(["id"=>$id])->with("cell")->one();
                break;
            case CommonGameStatus::TYPE_ESTATE_UTILITY:
                $statusModel=Utility::find()->where(["id"=>$id])->with("cell")->one();
                break;
            default:
                throw new Exception("Can't handle the next type",$type_id);
                break;
        }

        /** @var Cell */
        $cell=$statusModel->cell;
        // защита от злоумышленников
        // можно купить только в свой ход и только если игрок стоит на клетке с property
        if (!$is_auction&&$player->in_auction===YesNo::NO
            ||!$game->isTurn($player->id) && !$cell->hasEqualPosition($player)) {
            throw new Exception("в данный момент вы не можете купить это место");
        }
        if ($player->canPay($statusModel->cost) === false)
            return ResponseHelper::Error("недостаточно средств"); //TO DO Аукцион
        if($is_auction){
            $player->in_auction=YesNo::NO;
        }
        $player->pay($statusModel->cost);

        $player_id=$player->id;
        $cell_id=$cell->id;

        $model = new CommonGameStatus();
        $model->cell_id=$cell_id;
        $model->game_session_id = $game->id;
        $model->player_id = $player_id;
        $model->estate_type_id = $type_id;
        $model->estate_id = $id;
        $model->save(false);
        $game->turn_stage = TurnStageHelper::FINISHED;
        $game->update();

        if($type_id===CommonGameStatus::TYPE_ESTATE_PROPERTY)
            $model=$this->configureGroup($model,$statusModel->group->id,$game->id);
        $data = [
            "player" => $player,
            "game" => $game,
            "estate"=>$model,
            "chatHelp"=>["estate_type_id"=>$model->estate_type_id,"estate_id"=>$model->estate_id]
        ];
        return ResponseHelper::Socket("estate-bought", $data);
    } 
    public function configureGroup(CommonGameStatus &$model,$group_id,$game_session_id)
    {
        $model->rent_state_id = 1;
        $model->group_id = $model->group_id;
        $model->is_group_full=YesNO::YES;
        $model->update(false);
        $iGroupFull = PropertyGameStatus::isGroupFull($group_id, $game_session_id);
        if ($iGroupFull)
            PropertyGameStatus::markGroupImprovable($group_id);
        return $model;
    }
    public function actionPayPropertyRent(int $player_from_id, int $player_to_id,int $id,int $type_id)
    {
        $user = User::me();

        list($player_from, $player_to) = Player::findAll([$player_from_id, $player_to_id]);

        /** @var PropertyGameStatus */
        $propertyGameStatus = CommonGameStatus::find()
            ->where(["estate_id" => $id,"estate_type_id"=>$type_id,"player_id" => $player_to_id])
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
        $game = $propertyGameStatus->gameSession;
        $game->turn_stage = TurnStageHelper::FINISHED;
        $game->update(false);

        $players = [$player_from->getAttributes(), $player_to->getAttributes()];
        $data = [
            "players" => $players,
            "game" => $propertyGameStatus->gameSession->getAttributes(),
            "player_to_id" => $player_to->id,
            "cost" => $rent_cost
        ];
        return ResponseHelper::Socket("players-and-game", $data);
    }
    public function actionPayRent(int $player_from_id, int $player_to_id,int $id,int $type_id)
    {
        $user = User::me();

        list($player_from, $player_to) = Player::findAll([$player_from_id, $player_to_id]);
        if($user->id !== $player_from->user_id)
            throw new Exception("Access denied");

        //Проверка против злоумышленников
        // if (empty($propertyGameStatus) && $propertyGameStatus->cell->position !== $player_from->position
        // )
        //     throw new Exception("This user has no such player");


        
        $rentCost=PropertyGameStatus::getRentCost($id,$type_id,$player_to_id);

        if (!$player_from->canPay($rentCost))
            throw new Exception("have no money");
        $player_from->payTo($player_to, $rentCost);

        $game=GameSession::findOne($player_from->id);
        $game->turn_stage = TurnStageHelper::FINISHED;
        $game->update(false);

        $players = [$player_from->getAttributes(), $player_to->getAttributes()];
        $data = [
            "players" => $players,
            "game" => $game->getAttributes(),
            "player_to_id" => $player_to->id,
            "cost" => $rentCost
        ];
        return ResponseHelper::Socket("players-and-game", $data);
    }

}
