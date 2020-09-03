<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "event_spyder".
 *
 * @property int $id
 * @property string|null $text
 * @property string|null $operation
 * @property int|null $money
 */
class EventSpyder extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'event_spyder';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['money'], 'integer'],
            [['text', 'operation'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'text' => 'Text',
            'operation' => 'Operation',
            'money' => 'Money',
        ];
    }
}
