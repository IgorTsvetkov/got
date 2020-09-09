<?php 
namespace app\helpers;

interface IPayRent{
    public function getRentCost(int $estate_id,int $estate_type_id,int $player_to_id):int;
}