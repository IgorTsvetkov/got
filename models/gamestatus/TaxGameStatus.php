<?php

namespace app\models\gamestatus;

use app\helpers\IPayRent;
use Yii;
use app\models\Player;
use Exception;

/**
 * This is the model class for table "tax_game_status".
 *
 * @property int $id
 * @property int|null $cell_id
 * @property int|null $estate_id
 * @property int|null $estate_type_id
 * @property int|null $player_id
 * @property int|null $game_session_id
 * 
 */
class TaxGameStatus extends CommonEstateGameStatus
{
    const TAX_ONE_BUILDING=25;
    const MAX_TAX_COUNT=4;

    public static function getRentCost(int $estate_id, int $estate_type_id, int $player_to_id,?int $roll_value=null): int
    {
        $count = self::find()
        ->where(["estate_type_id" => $estate_type_id, "player_id" => $player_to_id])
        ->count();
        
        return self::getTaxRentByCount($count);
    }
    //COUNT-RENT_COST 1-25 2-50 3-100 4-200
    public static function getTaxRentByCount($count):int{
        if($count==0||$count>TaxGameStatus::MAX_TAX_COUNT)
            throw new Exception("Tax max count have to be more than 0 and less than ".self::MAX_TAX_COUNT.". You have ". $count);
        if($count==1)
            return self::TAX_ONE_BUILDING;          
        return 2*self::getTaxRentByCount(--$count);
    }
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['tax_id', 'tax_level', 'player_id', 'game_session_id'], 'integer'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'tax_id' => 'Tax ID',
            'tax_level' => 'Tax Level',
            'player_id' => 'Player ID',
            'game_session_id' => 'Game Session ID',
        ];
    }
}
