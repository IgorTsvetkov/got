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
            [['created_at', 'started_at', 'finished_at'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'created_at' => 'Created At',
            'started_at' => 'Started At',
            'finished_at' => 'Finished At',
        ];
    }

    /**
     * Gets query for [[Players]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPlayers()
    {
        return $this->hasMany(Player::className(), ['id' => 'player_id'])->viaTable('player_game_session', ['game_session_id' => 'id']);
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
}
