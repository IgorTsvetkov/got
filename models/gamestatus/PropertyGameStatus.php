<?php

namespace app\models\gamestatus;

use Yii;
use app\helpers\YesNo;
use app\models\Player;
use app\helpers\IPayRent;
use app\models\RentState;
use app\models\GameSession;
use app\models\PropertyGroup;
use app\models\estate\Property;

/**
 * This is the model class for table "property_player".
 *
 * @property int $id
 * @property int|null $rent_state_id
 * @property int|null $estate_id
 * @property int|null $estate_type_id
 * @property int|null $player_id
 * @property int|null $game_session_id
 * @property int|null $group_id
 * @property int|null $cell_id
 * @property bool|null $is_group_full
 */
class PropertyGameStatus extends CommonEstateGameStatus
{
    //equal rent_state table row count
    public const MAXRENTLEVEL = 6;

    public static function getRentCost(int $estate_id, int $estate_type_id, int $player_to_id,int $roll_value=null): int
    {
        $propertyGameStatus = self::find()
            ->where(["estate_id" => $estate_id, "estate_type_id" => $estate_type_id, "player_id" => $player_to_id])
            ->with(["property", "rentState"])
            ->limit(1)
            ->one();

        $rent_state_name = $propertyGameStatus->rentState->name;
        /** @var Property */
        $property = $propertyGameStatus->property;
        $rent_cost = $property->getAttribute($rent_state_name);
        return $rent_cost;
    }
    public function connectToGroupAndUpdate($group_id)
    {
        $this->rent_state_id = 1;
        $this->group_id = $group_id;
        $this->is_group_full=YesNO::YES;
        $this->update(false);
        $iGroupFull = self::isGroupFull($group_id, $this->game_session_id);
        if ($iGroupFull)
            self::markGroupImprovable($group_id);
    }
    public static function markGroupImprovable($group_id)
    {
        self::updateAll(["is_group_full" => YesNo::YES], ["in", "group_id", $group_id]);
    }
    public static function isGroupFull(int $group_id, int $game_session_id): bool
    {
        $counts = self::find()
            ->select(["COUNT(*) as count", "count_max"])
            ->where(["group_id" => $group_id])
            ->andWhere(["game_session_id" => $game_session_id])
            ->joinWith("group")
            ->groupBy("group_id")
            ->having("COUNT(DISTINCT player_id)=1")
            ->asArray()
            ->limit(1)
            ->one();

        return $counts["count"] === $counts["count_max"];
    }

    public function levelUp(): bool
    {
        if ($this->rent_state_id < self::MAXRENTLEVEL) {
            $this->rent_state_id += 1;
            $this->update();
            return true;
        }
        return false;
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['rent_state_id', 'player_id', 'game_session_id'], 'integer'],
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
            'player_id' => 'Player ID',
            'game_session_id' => 'Game Session ID',
        ];
    }
    public function getProperty()
    {
        return $this->hasOne(Property::class, ["id" => "estate_id"]);
    }
    public function getRentState()
    {
        return $this->hasOne(RentState::class, ["id" => "rent_state_id"]);
    }
    public function getGroup()
    {
        return $this->hasOne(PropertyGroup::class, ["id" => "group_id"]);
    }
}
