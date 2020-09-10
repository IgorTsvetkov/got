<?php

namespace app\controllers;

use Yii;
use Exception;
use app\models\User;
use app\helpers\YesNo;
use app\models\Player;
use app\helpers\IPayRent;
use app\models\estate\Tax;
use yii\helpers\VarDumper;
use app\models\GameSession;
use yii\filters\VerbFilter;
use app\models\estate\Utility;
use app\helpers\ResponseHelper;
use app\models\estate\Property;
use app\helpers\TurnStageHelper;
use app\helpers\EstateTypeHelper;
use app\helpers\GameStatusHelper;
use app\models\gamestatus\PropertyGameStatus;
use app\models\gamestatus\CommonEstateGameStatus;

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
        /** @var CommonEstateGameStatus */
        $isBought = CommonEstateGameStatus::exist($type_id,$id,$player->game_session_id);
        if ($isBought)
            return ResponseHelper::Error("уже куплено");
        /** @var GameSession */
        $game=$player->gameSession;
        //это этот игрок?
        if ($player->user->id !== Yii::$app->user->id)
            return ResponseHelper::Error("вы не можете использовать данные другого пользователя");
        $statusModel=null;
        switch ($type_id) {
            case EstateTypeHelper::PROPERTY:
                $statusModel=Property::find()->where(["id" => $id])->with(["cell", "group"])->one();
                break;
            case EstateTypeHelper::TAX:
                $statusModel=Tax::find()->where(["id"=>$id])->with("cell")->one();
                break;
            case EstateTypeHelper::UTILITY:
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

        $model = new CommonEstateGameStatus();
        $model->cell_id=$cell_id;
        $model->game_session_id = $game->id;
        $model->player_id = $player_id;
        $model->estate_type_id = $type_id;
        $model->estate_id = $id;
        $model->save(false);
        $game->turn_stage = TurnStageHelper::FINISHED;
        $game->update();

        if($type_id===EstateTypeHelper::PROPERTY)
            $model=$this->configureGroup($model,$statusModel->group->id,$game->id);
        $data = [
            "player" => $player,
            "game" => $game,
            "estate"=>$model,
            "chatHelp"=>["estate_type_id"=>$model->estate_type_id,"estate_id"=>$model->estate_id]
        ];
        return ResponseHelper::Socket("estate-bought", $data);
    } 
    public function configureGroup(CommonEstateGameStatus &$model,$group_id,$game_session_id)
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
    public function actionView(int $type_id,int $id)
    {
        $game=GameSession::me()->one();
        $game_session_id = $game->id;
        $property = Property::find()
            ->where(["id" => $id])
            ->with(["propertyGameStatuses" => function ($query) use ($game_session_id) {
                $query->where(["game_session_id" => $game_session_id]);
                $query->with(["player.user" => function ($query) {
                    $query->select("username");
                }, "player.hero", "rentState"]);
            }])
            ->limit(1)
            ->asArray()
            ->one();
        $property["propertyGameStatus"] = $property["propertyGameStatuses"][0];
        unset($property["propertyGameStatuses"]);
        return ResponseHelper::Success($property);
    }
    public function actionPayRent(int $player_to_id,int $id,int $type_id,$roll_dice=null)
    {
        $user = User::me();
        $player_from=Player::me()->with("gameSession")->one();
        $player_to=Player::findOne($player_to_id);
        /** @var GameSession */
        $game=$player_from->gameSession;
        
        if($user->id !== $player_from->user_id)
            throw new Exception("Access denied");

        //Проверка против злоумышленников
        // if (empty($propertyGameStatus) && $propertyGameStatus->cell->position !== $player_from->position
        // )
        //     throw new Exception("This user has no such player");


        // $rentCost=PropertyGameStatus::getRentCost($id,$type_id,$player_to_id);

        /** @var IPayRent */
        $gameStatus=GameStatusHelper::getClassByType($type_id);
        $roll_value=null;
        if($gameStatus->isNeedRollForPayRent())
        {
            if($game->turn_stage!==TurnStageHelper::ROLL_AGAIN_FINISH)
                throw new Exception("Access denied");
            $roll_value=$game->getRollCount();
        }
        $rentCost=$gameStatus->getRentCost($id,$type_id,$player_to_id,$roll_value);
        if (!$player_from->canPay($rentCost))
            throw new Exception("have no money");
        $player_from->payTo($player_to, $rentCost);

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
