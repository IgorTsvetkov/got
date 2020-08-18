<?php

namespace app\models;

use Yii;
use app\models\User;
use app\models\GameSession;
use yii\behaviors\BlameableBehavior;

/**
 * This is the model class for table "player".
 *
 * @property int $id
 * @property int|null $user_id
 * @property int|null $game_session_id
 * @property int|null $hero_id
 * @property int|null $slot
 * @property int|null $position
 *

 * @property GameSession[] $gameSessions
 * @property User[] $users
 */
class Player extends \yii\db\ActiveRecord
{    
    public static function createAndLink(GameSession $game,User $user){
        $game->link("users",$user);
        $player=new Player();
        $player->hero_id=1;//default hero: faceless men  
        $player->slot=$game->getFirstEmptySlot();
        $player->user_id=$user->id;
        $player->game_session_id=$game->id;
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
}
