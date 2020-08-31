<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "property_group".
 *
 * @property int $id
 * @property string|null $color_name
 * @property int $count_max
 */
class PropertyGroup extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'property_group';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['color_name'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'color_name' => 'Color Name',
        ];
    }
}
