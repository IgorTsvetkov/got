<?php

namespace app\models\gamestatus;

use app\helpers\IPayRent;
use app\models\Cell;
use Yii;
use app\models\Player;
use app\models\GameSession;
use Exception;

/**
 * This is the model class for table "common_game_status".
 *
 * @property int $id
 * @property int|null $cell_id
 * @property int|null $game_session_id
 * @property int|null $player_id
 * @property int|null $estate_type_id
 * @property int|null $estate_id
 * @property int|null $rent_state_id
 * @property int|null $group_id
 * @property int|null $is_group_full
 */
class CommonEstateGameStatus extends \yii\db\ActiveRecord implements IPayRent
{

    public static function getRentCost(int $estate_id, int $estate_type_id, int $player_to_id,?int $roll_value=null): int {
        throw new Exception("Not implemented");
    }

    public static function isNeedRollForPayRent(): bool {
        return false;
    }    
    public static function exist(int $type,int $id,int $game_session_id)
    {
        return CommonEstateGameStatus::find()
        ->where(["estate_type_id" => $type])
        ->andWhere(["estate_id" => $id])
        ->andWhere(["game_session_id" =>$game_session_id])
        ->exists();
    }
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'common_game_status';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['cell_id', 'game_session_id', 'player_id', 'estate_type_id', 'estate_id', 'rent_state_id', 'group_id', 'is_group_full'], 'integer'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'cell_id' => 'Cell ID',
            'game_session_id' => 'Game Session ID',
            'player_id' => 'Player ID',
            'estate_type_id' => 'Estate Type ID',
            'estate_id' => 'Estate ID',
            'rent_state_id' => 'Rent State ID',
            'group_id' => 'Group ID',
            'is_group_full' => 'Is Group Full',
        ];
    }
    public function getPlayer()
    {
        return $this->hasOne(Player::class,["id"=>"player_id"]);
    }
    public function getGameSession()
    {
        return $this->hasOne(GameSession::class,["id"=>"game_session_id"]);
    }
    public function getCell()
    {
        return $this->hasOne(Cell::class,["id"=>"cell_id"]);
    }
}
