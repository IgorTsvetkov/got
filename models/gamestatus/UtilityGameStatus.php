<?php

namespace app\models\gamestatus;

use Yii;
use Exception;
use app\helpers\IPayRent;

/**
 * This is the model class for table "utility_game_status".
 *
 * @property int $id
 * @property int|null $cell_id
 * @property int|null $estate_id
 * @property int|null $estate_type_id
 * @property int|null $player_id
 * @property int|null $game_session_id
 * 
 */
class UtilityGameStatus extends CommonEstateGameStatus
{
    const MULTIPLIER_ONE_BUILDING = 4;
    const MULTIPLIER_TWO_BUILDING = 10;
    public static function getRentCost(int $estate_id, int $estate_type_id, int $player_to_id,?int $roll_value=null): int
    {
        if(empty($roll_value))
            throw new Exception("roll value can't be empty");   
        $count = self::find()
            ->where(["estate_type_id" => $estate_type_id, "player_id" => $player_to_id])
            ->count();
        $multiplier=self::getMultiplierByCount($count);
        return $multiplier*$roll_value;
    }
    public static function isNeedRollForPayRent(): bool
    {
        return true;
    }
    private static function getMultiplierByCount($count)
    {
        if($count===1)
            return self::MULTIPLIER_ONE_BUILDING;
        if($count===2)
            return self::MULTIPLIER_TWO_BUILDING;
        throw new Exception("Can't get roll Multiplier count value $count");
        
    }
    private function getUtilityRentByCount($count,$rollvalue){
        
    }
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['utility_id', 'player_id', 'game_session_id'], 'integer'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'utility_id' => 'Utility ID',
            'player_id' => 'Player ID',
            'game_session_id' => 'Game Session ID',
        ];
    }
}
