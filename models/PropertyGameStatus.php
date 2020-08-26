<?php

namespace app\models;

use Yii;
use app\models\RentState;
use phpDocumentor\Reflection\Types\Boolean;

/**
 * This is the model class for table "property_player".
 *
 * @property int $id
 * @property int|null $rent_state_id
 * @property int|null $property_id
 * @property int|null $player_id
 * @property int|null $game_session_id
 */
class PropertyGameStatus extends \yii\db\ActiveRecord
{
    //equal rent_state table row count
    public const MAXRENTLEVEL=6;

    public function levelUp():bool{
        if($this->rent_status_id<self::MAXRENTLEVEL){
            $this->rent_status_id++;
            $this->update();
            return true;
        }
        return false;
    }
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'property_game_status';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['rent_state_id', 'property_id', 'player_id', 'game_session_id'], 'integer'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'rent_state_id' => 'Rent State ID',
            'property_id' => 'Property ID',
            'player_id' => 'Player ID',
            'game_session_id' => 'Game Session ID',
        ];
    }
    public function getProperty()
    {
        return $this->hasOne(Property::class,["id"=>"property_id"]);
    }
    public function getPlayer()
    {
        return $this->hasOne(Player::class,["id"=>"player_id"]);
    }
    public function getRentState()
    {
        return $this->hasOne(RentState::class,["id"=>"rent_state_id"]);
    }
}
