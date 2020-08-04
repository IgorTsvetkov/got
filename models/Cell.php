<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "cell".
 *
 * @property int $id
 * @property int|null $position
 * @property string|null $event_id
 * @property string|null $castle_id
 */
class Cell extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'cell';
    }
    public function extraFields()
    {
        return ['castle'];
    }
    public function getCastle()
    {
        return $this->hasOne(Castle::className(),["id"=>"castle_id"]);
    }
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['position'], 'integer'],
            [['event_id', 'castle_id'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'position' => 'Position',
            'event_id' => 'Event ID',
            'castle_id' => 'Castle ID',
        ];
    }
}
