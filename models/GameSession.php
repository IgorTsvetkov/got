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
    public function setUserInSlot(User $user,int $slot=1){
        $this->link("users",$user);

        $player=new Player(); 
          
        $player->slot=$slot;
        $player->user_id=$user->id;
        $player->game_session_id=$this->id;
        $player->save();
    }
    public function removeUser($user)
    {
        $junction=UserGameSession::findOne(["user_id"=>$user->id,"game_session_id"=>$this->id]);
        $junction->delete();
        // $junction->save();
        $player=Player::find()->where(["user_id"=>$user->id])->orderBy(['game_session_id'=>SORT_DESC])->limit(1)->one();
        $player->delete();
        // $player->save();
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
