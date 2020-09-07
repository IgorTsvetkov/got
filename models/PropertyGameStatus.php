<?php

namespace app\models;

use Yii;
use app\helpers\YesNo;
use app\models\RentState;
use yii\helpers\VarDumper;
use phpDocumentor\Reflection\Types\Boolean;

/**
 * This is the model class for table "property_player".
 *
 * @property int $id
 * @property int|null $rent_state_id
 * @property int|null $property_id
 * @property int|null $player_id
 * @property int|null $game_session_id
 * @property int|null $group_id
 * @property int|null $cell_id
 * @property bool|null $is_group_full
 */
class PropertyGameStatus extends \yii\db\ActiveRecord
{
    //equal rent_state table row count
    public const MAXRENTLEVEL=6;

    public static function markGroupImprovable($group_id){
        self::updateAll(["is_group_full"=>YesNo::YES],["in","group_id",$group_id]);
    }
    public static function isMonopoly(int $group_id,int $game_session_id):bool{
        $counts=self::find()
        ->select(["COUNT(*) as count","count_max"])
        ->where(["group_id"=>$group_id])
        ->andWhere(["game_session_id"=>$game_session_id])
        ->joinWith("group")
        ->groupBy("group_id")
        ->having("COUNT(DISTINCT player_id)=1")
        ->asArray()
        ->limit(1)
        ->one();

        return $counts["count"]===$counts["count_max"];
    }

    public function levelUp():bool{
        if($this->rent_state_id<self::MAXRENTLEVEL){
            $this->rent_state_id+=1;
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
    public function getGameSession()
    {
        return $this->hasOne(GameSession::class,["id"=>"game_session_id"]);
    }
    public function getRentState()
    {
        return $this->hasOne(RentState::class,["id"=>"rent_state_id"]);
    }
    public function getGroup()
    {
        return $this->hasOne(PropertyGroup::class,["id"=>"group_id"]);
    }
}
