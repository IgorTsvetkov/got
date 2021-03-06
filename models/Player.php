<?php

namespace app\models;

use Yii;
use Exception;
use yii\db\Query;
use app\models\User;
use yii\helpers\VarDumper;
use app\models\GameSession;
use app\models\estate\Property;
use app\helpers\EstateTypeHelper;
use app\models\UtilityGameStatus;
use app\models\estate\Estate;
use yii\behaviors\BlameableBehavior;
use app\models\gamestatus\PropertyGameStatus;
use app\models\gamestatus\CommonEstateGameStatus;

/**
 * This is the model class for table "player".
 *
 * @property int $id
 * @property int|null $user_id
 * @property int|null $game_session_id
 * @property int|null $hero_id
 * @property int|null $slot
 * @property int|null $position
 * @property int|null $money
 * @property int|null $in_auction
 *

 * @property GameSession $gameSession
 * @property User $user
 */
class Player extends \yii\db\ActiveRecord
{
    public const COUNT_POSITION = 40;
    public const PREVIOUS_SLOT = 111;
    public const NEXT_SLOT = 112;
    public function buy(Estate $estate,$cost):CommonEstateGameStatus{
        $this->pay($cost);
        $model = new CommonEstateGameStatus();
        $model->cell_id=$estate->cell->id;
        $model->game_session_id = $this->game_session_id;
        $model->player_id = $this->id;
        $model->estate_type_id = $estate::getEstateType();
        $model->estate_id = $estate->id;
        if ($estate::getEstateType()==EstateTypeHelper::PROPERTY){
            $group_id=$estate->group->id;
            $model->rent_state_id = 1;
            $model->group_id = $group_id;
            $isGroupFull = PropertyGameStatus::isGroupFull($group_id, $this->game_session_id);
            if ($isGroupFull)
                PropertyGameStatus::markGroupImprovable($group_id);
            }
        $model->save(false);
        return $model;
    }
    public function isOwnEstateOnCell(){
        $estateStatus=Cell::getCommonEstateGameStatus($this->game_session_id,$this->position);
        if(empty($estateStatus))
            return false;
        $owner_player_id=$estateStatus->player_id;
        return $this->id===$owner_player_id;
    }
    public function isUtilityRollRequired(){
        $estateStatus=Cell::getCommonEstateGameStatus($this->game_session_id,$this->position);
        // VarDumper::dump($estateStatus,10,true);
        if($estateStatus->estate_type_id==EstateTypeHelper::UTILITY)
            return true;
        return false;
    }
    public function canFinishTurn(){
        return $this->isOwnEstateOnCell();
    }
    public static function meOne(): ?self
    {
        return self::me()->one();
    }
    public static function me()
    {
        return Player::find()->where(["user_id" => Yii::$app->user->id])->orderBy(['id' => SORT_DESC])->limit(1);
    }
    public function teleportToProperty($propertyName)
    {
        list($position) = Property::find()->select("cell.position as position")->where(["name" => $propertyName])->joinWith("cell")->column("position");
        if (empty($position))
            throw new Exception("teleport position is empty");
        $this->position = $position;
        $this->update(false);
    }
    public function canPay($cost)
    {
        if ($this->money < $cost)
            return false;
        return true;
    }
    public function pay($value)
    {
        $this->money -= $value;
        $this->update(false);
    }
    public function earn(int $value)
    {
        $this->money += $value;
        $this->update(false);
    }
    public function payTo($player_to, $cost = 0)
    {
        $this->money -= $cost;
        $player_to->money += $cost;
        $this->update();
        $player_to->update();
    }
    public function move($value)
    {
        $this->position += $value;
        $this->position %= self::COUNT_POSITION;
        $this->update();
    }
    public function getNextTurnPlayer($from = null): ?self
    {
        if (empty($from))
            $from = self::tableName();
        $game_session_id = $this->game_session_id;
        $slot = $this->slot;
        //Take the next player slot
        /** @var Query */
        $nextSlotNullableQuery = (new Query())->select(["slot"])->from($from)->where(["game_session_id" => $game_session_id])->andWhere([">", "slot", $slot])->orderBy(["slot" => SORT_ASC]);

        //Take the first player slot
        /** @var Query */
        $firstSlotQuery = (new Query())->select(["slot" => "MIN(slot)"])->from($from)->where(["game_session_id" => $game_session_id]);

        //If the NEXT slot is empty take the FIRST slot 
        $nextSlotQuery = (new Query)->select("slot")->from($nextSlotNullableQuery->union($firstSlotQuery))->limit(1);

        $nextTurnPlayer = self::takePlayerBySlot($game_session_id, $nextSlotQuery);
        return $nextTurnPlayer;
    }
    public static function takePlayerBySlot($game_session_id, $slotCanBeQuery): ?self
    {
        $player = Player::find()
            ->where(["game_session_id" => $game_session_id])
            ->andWhere(["=", "slot", $slotCanBeQuery])
            ->one();
        return $player;
    }
    public function getPreviousTurnPlayer(): ?self
    {
        $game_session_id = $this->game_session_id;
        $slot = $this->slot;
        //Take previous player slot
        /** @var Query */
        $previousNullableQuery = (new Query())->select(["slot"])->from(Player::tableName())->where(["game_session_id" => $game_session_id])->andWhere(["<", "slot", $slot])->orderBy(["slot" => SORT_ASC]);

        //Take the last player slot
        /** @var Query */
        $lastSlotQuery = (new Query())->select(["slot" => "MAX(slot)"])->from(Player::tableName())->where(["game_session_id" => $game_session_id]);

        //If PREVIOUS slot is empty take the LAST slot 
        $previousSlotQuery = (new Query)->select("slot")->from($previousNullableQuery->union($lastSlotQuery))->limit(1);

        $previousPlayer = self::takePlayerBySlot($this->game_session_id, $previousSlotQuery);

        return $previousPlayer;
    }
    public static function createAndLink(GameSession $game, User $user)
    {
        $game->link("users", $user);
        $player = new Player();
        $player->hero_id = 1; //default hero: faceless men  
        $player->slot = $game->getFirstEmptySlot();
        $player->position = GameSession::START_PLAYER_POSITION;
        $player->user_id = $user->id;
        $player->game_session_id = $game->id;
        $player->money = GameSession::START_MONEY;
        return $player;
    }

    function init()
    {
        $this->position = 0;
    }
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'player';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['game_session_id', "hero_id", 'slot', 'position'], 'integer'],
            [['game_session_id', "hero_id", 'slot', 'position'], 'required']
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'slot' => 'Slot',
            'position' => 'Position',
        ];
    }

    /**
     * Gets query for [[Hero]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getHero()
    {
        return $this->hasOne(Hero::class, ['id' => 'hero_id']);
    }

    /**
     * Gets query for [[GameSessions]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getGameSession()
    {
        return $this->hasOne(GameSession::class, ['id' => 'game_session_id']);
    }

    /**
     * Gets query for [[Users]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::class, ['id' => 'user_id']);
    }
    // public function getPropertyGameStatuses()
    // {
    //     return $this->hasMany(PropertyGameStatus::class, ["player_id" => "id"]);
    // }
    // public function getTaxGameStatuses()
    // {
    //     return $this->hasMany(TaxGameStatus::class, ["player_id" => "id"]);
    // }
    // public function getUtilityGameStatuses()
    // {
    //     return $this->hasMany(UtilityGameStatus::class, ["player_id" => "id"]);
    // }
    // public function getGameProperties()
    // {
    //     return $this->hasMany(Property::class, ["id" => "property_id"])->via("propertyGameStatuses");
    // }
    // public function getPropertyCells()
    // {
    //     return $this->hasMany(Cell::class, ["property_id" => "id"])->via("gameProperties");
    // }
    public function getAuction()
    {
        return $this->hasOne(Auction::class, ["turn_player_id" => "id"]);
    }
    public function getEstates()
    {
        return $this->hasMany(CommonEstateGameStatus::class,["player_id"=>"id"]);
    }
}
