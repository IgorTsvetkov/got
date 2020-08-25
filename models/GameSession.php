<?php

namespace app\models;

use Yii;
use app\models\User;
use app\models\Player;
use yii\behaviors\TimestampBehavior;
use yii\db\Expression;

/**
 * This is the model class for table "game_session".
 *
 * @property int $id
 * @property string|null $name
 * @property int|null $leader_user_id
 * @property int|null $turn_player_id
 * @property string|null $created_at
 * @property string|null $started_at
 * @property string|null $finished_at
 *
 * @property PlayerGameSession[] $playerGameSessions
 * @property Player[] $players
 * @property UserGameSession[] $userGameSessions
 * @property User[] $users
 */
class GameSession extends \yii\db\ActiveRecord
{
    public const MAX_PLAYERS=6;
    public function getFirstEmptySlot():int{
        $slots=GameSession::find()->where(["game_session.id"=>$this->id])->joinWith(["players"])->select("slot")->asArray()->all();
        $filledSlot=array_map(function($slot){
            return $slot["slot"];
        },$slots);
        $possibleSlot=[0,1,2,3,4,5];
        $freeSlots=array_diff($possibleSlot,$filledSlot);
        return array_key_first($freeSlots);
    }
    public function removeUser($user_id)
    {
        $junction=UserGameSession::find()->where(["user_id"=>$user_id,"game_session_id"=>$this->id])->limit(1)->one();
        // var_dump($junction);
        $junction->delete();
        $player=Player::find()->where(["user_id"=>$user_id])->orderBy(['game_session_id'=>SORT_DESC])->limit(1)->one();
        $player->delete();
    }
    public function getIsStarted():bool{
        return $this->started_at!=null?true:false;
    }
    public function getIsFinished():bool{
        return $this->finished_at!=null?true:false;
    }
    public function behaviors()
    {
        return[
            [
                'class'=>TimestampBehavior::class,
                'createdAtAttribute'=>"created_at",
                'updatedAtAttribute'=>false,
                'value'=>new Expression('NOW()')    
            ]
        ];
    }
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'game_session';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name','created_at', 'started_at', 'finished_at'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'Id матча',
            'name'=>"Название",
            'leader.username'=>"Имя пользователя",
            'created_at' => 'Комната создана',
            'started_at' => 'Игра начата',
            'finished_at' => 'Игра закончена',
        ];
    }

    /**
     * Gets query for [[Players]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPlayers()
    {
        return $this->hasMany(Player::className(), ['game_session_id' => 'id']);
    }
    public function getLeader()
    {
        return $this->hasOne(User::className(), ['id' => 'leader_user_id']);
    }
    /**
     * Gets query for [[Users]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUsers()
    {
        return $this->hasMany(User::className(), ['id' => 'user_id'])->viaTable('user_game_session', ['game_session_id' => 'id']);
    }
    /**
     * Gets query for [[UserGameSessions]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUserGameSessions()
    {
        return $this->hasMany(UserGameSession::className(), ['game_session_id' => 'id']);
    } 
}
