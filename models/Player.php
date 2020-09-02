<?php

namespace app\models;

use Yii;
use app\models\User;
use app\models\GameSession;
use Exception;
use yii\behaviors\BlameableBehavior;
use yii\db\Query;

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
 *

 * @property GameSession $gameSession
 * @property User $user
 */
class Player extends \yii\db\ActiveRecord
{
    public const COUNT_POSITION=40;
    public const PREVIOUS_SLOT=111;
    public const NEXT_SLOT=112;
    public function me():?self
    {
        return Player::find()->where(["user_id"=>Yii::$app->user->id])->orderBy(['id'=>SORT_DESC])->limit(1)->one();
    }
    public function teleportToProperty($propertyName){
        list($position)=Property::find()->select("cell.position as position")->where(["name"=>$propertyName])->joinWith("cell")->column("position");
        if(empty($position))
            throw new Exception("teleport position is empty");        
        $this->position=$position;
        $this->update(false);
    }
    public function canPay($cost){
        if($this->money<$cost)
            return false;
        return true;
    }
    public function pay($value){
        $this->money-=$value;
        $this->update(false);
    }
    public function earn(int $value){
        $this->money+=$value;
        $this->update(false);
    }
    public function payTo($player_to,$cost=0){
        $this->money-=$cost;
        $player_to->money+=$cost;
        $this->update();
        $player_to->update();
    }
    public function move($value){
        $this->position += $value;
        $this->position %= self::COUNT_POSITION;
        $this->update();
    }
    public function getNextTurnPlayer():?self
    {
        $game_session_id=$this->game_session_id;
        $slot=$this->slot;
        //Take the next player slot
        /** @var Query */
        $nextSlotNullableQuery=(new Query())->select(["slot"])->from(Player::tableName())->where(["game_session_id"=>$game_session_id])->andWhere([">","slot",$slot])->orderBy(["slot"=>SORT_ASC]);

        //Take the first player slot
        /** @var Query */
        $firstSlotQuery=(new Query())->select(["slot"=>"MIN(slot)"])->from(Player::tableName())->where(["game_session_id"=>$game_session_id]);
        
        //If the NEXT slot is empty take the FIRST slot 
        $nextSlotQuery=(new Query)->select("slot")->from($nextSlotNullableQuery->union($firstSlotQuery))->limit(1);
        
        $nextTurnPlayer=self::takePlayerBySlot($game_session_id,$nextSlotQuery);
        return $nextTurnPlayer;
    } 
    public static function takePlayerBySlot($game_session_id, $slotCanBeQuery):?self{
        $player = Player::find()
        ->where(["game_session_id"=>$game_session_id])
        ->andWhere(["=","slot",$slotCanBeQuery])
        ->one();
        return $player;
    }   
    public function getPreviousTurnPlayer():?self
    {
        $game_session_id=$this->game_session_id;
        $slot=$this->slot;
        //Take previous player slot
        /** @var Query */
        $previousNullableQuery=(new Query())->select(["slot"])->from(Player::tableName())->where(["game_session_id"=>$game_session_id])->andWhere(["<","slot",$slot])->orderBy(["slot"=>SORT_ASC]);

        //Take the last player slot
        /** @var Query */
        $lastSlotQuery=(new Query())->select(["slot"=>"MAX(slot)"])->from(Player::tableName())->where(["game_session_id"=>$game_session_id]);
        
        //If PREVIOUS slot is empty take the LAST slot 
        $previousSlotQuery=(new Query)->select("slot")->from($previousNullableQuery->union($lastSlotQuery))->limit(1);

        $previousPlayer=self::takePlayerBySlot($this->game_session_id,$previousSlotQuery);

        return $previousPlayer;
    }
    public static function createAndLink(GameSession $game,User $user){
        $game->link("users",$user);
        $player=new Player();
        $player->hero_id=1;//default hero: faceless men  
        $player->slot=$game->getFirstEmptySlot();
        $player->user_id=$user->id;
        $player->game_session_id=$game->id;
        $player->money=200000;
        return $player;
    }

    function init()
    {
        $this->position=0;
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
            [['game_session_id',"hero_id",'slot', 'position'], 'integer'],
            [['game_session_id',"hero_id",'slot','position'],'required']
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
    public function getPropertyGameStatuses(){
        return $this->hasMany(PropertyGameStatus::class,["player_id"=>"id"]);
    }
    public function getGameProperties(){
        return $this->hasMany(Property::class,["id"=>"property_id"])->via("propertyGameStatuses");
    }
    public function getPropertyCells(){
        return $this->hasMany(Cell::class,["property_id"=>"id"])->via("gameProperties");
    }
    public function getPositionCell(){
        return $this->hasOne(Cell::class,["position"=>"position"]);
    }
}
