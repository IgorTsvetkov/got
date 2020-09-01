<?php
namespace app\helpers;

use app\models\User;
use app\models\Player;
use app\models\PropertyGameStatus;

class SpiderEventHelper{
    public static function calculateWildfireDamageCost($player_id)
    {
        list("homeCount"=>$homeCount,"innCount"=>$innCount) 
        = PropertyGameStatus::find()
            ->select([
                "homeCount" => "SUM(name LIKE '%home%')",
                "innCount" => "SUM(name LIKE '%inn%')"
            ])
            ->where(["player_id" => $player_id])
            ->leftJoin("rent_state as rs", "rs.id=property_game_status.rent_state_id")
            ->asArray()
            ->one();
        //стоимость ущерба(указаны в карточке)
        $damage_home_cost=40;
        $damage_inn_cost=115;

        $damage_cost=$damage_home_cost*$homeCount+$damage_inn_cost*$innCount;
        return $damage_cost;
    }
}