<?php 
namespace app\helpers;

interface IPayRent{
    public static function getRentCost(int $estate_id,int $estate_type_id,int $player_to_id,?int $roll_value=null):int;
    public static function isNeedRollForPayRent():bool;
}