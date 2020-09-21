<?php

namespace app\controllers;

use Yii;
use Exception;
use app\models\User;
use app\helpers\YesNo;
use app\models\Player;
use app\models\Auction;
use app\helpers\IPayRent;
use app\models\estate\Tax;
use yii\helpers\VarDumper;
use app\models\GameSession;
use yii\filters\VerbFilter;
use app\helpers\EstateManager;
use app\models\estate\Utility;
use app\helpers\ResponseHelper;
use app\models\estate\Property;
use app\helpers\TurnStageHelper;
use app\helpers\EstateTypeHelper;
use app\helpers\GameStatusHelper;
use app\models\estate\Base\IEstate;
use app\models\gamestatus\PropertyGameStatus;
use app\models\gamestatus\CommonEstateGameStatus;
use Symfony\Component\Finder\Exception\AccessDeniedException;

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
    // private function buy(int $type_id, int $id, $player_id, $auction_cost)
    // {
    //     $estate = EstateHelper::getEstate($type_id, $id);
    //     $player = Player::findOne($player_id);
    //     $cost = isset($auction_cost) ? $auction_cost : $estate->cost;
    //     if (!$player->canPay($cost))
    //         return ResponseHelper::Error("Недостаточно средств");
    //     $estateGameStatus = $player->buy($estate, $cost);
    //     return $estateGameStatus;
    // }

    public function actionBuy(int $type_id, int $id)
    {
        /** @var Player */
        $player = Player::me()->with("gameSession")->one();
        /** @var CommonEstateGameStatus */
        $isBought = CommonEstateGameStatus::exist($type_id, $id, $player->game_session_id);
        if ($isBought)
            return ResponseHelper::Error("уже куплено");
        /** @var GameSession */
        $game = $player->gameSession;
        //это этот игрок?
        if ($player->user_id !== Yii::$app->user->id)
            return ResponseHelper::Error("вы не можете использовать данные другого пользователя");
        $manager =new  EstateManager($type_id);
        $estate=$manager->getEstate($id);
        /** @var Cell */
        $cell = $estate->cell;
        // защита от злоумышленников
        // можно купить только в свой ход и только если игрок стоит на клетке с property
        if (!$game->isTurn($player->id) && !$cell->hasEqualPosition($player)) {
            throw new Exception("в данный момент вы не можете купить это место");
        }

        $cost = isset($auction_cost) ? $auction_cost : $estate->cost;
        if (!$player->canPay($cost))
            return ResponseHelper::Error("Недостаточно средств");
        $estateGameStatus = $player->buy($estate, $cost);
        $game->turn_stage=TurnStageHelper::FINISHED;
        $data = [
            "player" => $player,
            "game" => $game,
            "estate" => $estateGameStatus,
            "chatHelp" => ["estate_type_id" => $estateGameStatus->estate_type_id, "estate_id" => $estateGameStatus->estate_id]
        ];
        return ResponseHelper::Socket("estate-bought", $data);
    }
    public function actionView(int $type_id, int $id)
    {
        $game = GameSession::me()->one();
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
    public function actionPayRent(int $player_to_id, int $id, int $type_id, $roll_dice = null)
    {
        $user = User::me();
        $player_from = Player::me()->with("gameSession")->one();
        $player_to = Player::findOne($player_to_id);
        /** @var GameSession */
        $game = $player_from->gameSession;

        if ($user->id !== $player_from->user_id)
            throw new AccessDeniedException();

        //Проверка против злоумышленников
        // if (empty($propertyGameStatus) && $propertyGameStatus->cell->position !== $player_from->position
        // )
        //     throw new Exception("This user has no such player");


        // $rentCost=PropertyGameStatus::getRentCost($id,$type_id,$player_to_id);

        /** @var IPayRent */
        $gameStatus = GameStatusHelper::getClassByType($type_id);
        $roll_value = null;
        if ($gameStatus->isNeedRollForPayRent()) {
            if ($game->turn_stage !== TurnStageHelper::ROLL_AGAIN_FINISH)
                throw new AccessDeniedException();
            $roll_value = $game->getRollSum();
        }
        $rentCost = $gameStatus->getRentCost($id, $type_id, $player_to_id, $roll_value);
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
