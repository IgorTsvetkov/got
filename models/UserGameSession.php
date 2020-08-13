<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "user_game_session".
 *
 * @property int $user_id
 * @property int $game_session_id
 *
 * @property GameSession $gameSession
 * @property User $user
 */
class UserGameSession extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'user_game_session';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_id', 'game_session_id'], 'required'],
            [['user_id', 'game_session_id'], 'integer'],
            [['user_id', 'game_session_id'], 'unique', 'targetAttribute' => ['user_id', 'game_session_id']],
            [['game_session_id'], 'exist', 'skipOnError' => true, 'targetClass' => GameSession::className(), 'targetAttribute' => ['game_session_id' => 'id']],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['user_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'user_id' => 'User ID',
            'game_session_id' => 'Game Session ID',
        ];
    }

    /**
     * Gets query for [[GameSession]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getGameSession()
    {
        return $this->hasOne(GameSession::className(), ['id' => 'game_session_id']);
    }

    /**
     * Gets query for [[User]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }
}
