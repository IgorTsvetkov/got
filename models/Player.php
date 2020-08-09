<?php

namespace app\models;

use Yii;
use app\models\User;
use app\models\GameSession;

/**
 * This is the model class for table "player".
 *
 * @property int $id
 * @property int|null $slot
 * @property int|null $position
 *

 * @property GameSession[] $gameSessions
 * @property User[] $users
 */
class Player extends \yii\db\ActiveRecord
{
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
            [['slot', 'position'], 'integer'],
            [['slot','position'],'required']
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
     * Gets query for [[GameSessions]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getGameSession()
    {
        return $this->hasOne(GameSession::class, ['id' => 'game_session_id'])->viaTable('player_game_session', ['player_id' => 'id']);
    }

    /**
     * Gets query for [[Users]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::class, ['id' => 'user_id'])->viaTable('player_user', ['player_id' => 'id']);
    }
}
