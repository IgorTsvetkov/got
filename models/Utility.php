<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "utility".
 *
 * @property int $id
 * @property string|null $name
 * @property string|null $src
 */
class Utility extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'utility';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name', 'src'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'src' => 'Src',
        ];
    }
}
