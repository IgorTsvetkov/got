<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "game_session".
 *
 * @property int $id
 * @property int|null $user_id
 * @property string|null $created_at
 * @property string|null $finished_at
 */
class GameSession extends \yii\db\ActiveRecord
{
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
            [['user_id'], 'integer'],
            [['created_at', 'finished_at'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_id' => 'User ID',
            'created_at' => 'Created At',
            'finished_at' => 'Finished At',
        ];
    }
}
