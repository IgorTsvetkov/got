<?php
namespace app\helpers;

use yii\db\Query;
use app\models\User;
use app\models\Player;
use app\models\PropertyGameStatus;

class SpyderEventHelper{
    public static function calculateWildfireDamageCost($player_id)
    {
        list("homeCount"=>$homeCount,"innCount"=>$innCount) 
        = (new Query)
            ->select([
                "homeCount" => "SUM(name LIKE '%home%')",
                "innCount" => "SUM(name LIKE '%inn%')"
            ])
            ->from(PropertyGameStatus::tableName())
            ->where(["player_id" => $player_id])
            ->limit(1)
            ->leftJoin("rent_state as rs", "rs.id=property_game_status.rent_state_id")
            ->one();
        //стоимость ущерба(указаны в карточке)
        $damage_home_cost=40;
        $damage_inn_cost=115;

        $damage_cost=$damage_home_cost*$homeCount+$damage_inn_cost*$innCount;
        return $damage_cost;
    }
}