<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "property".
 *
 * @property int $id
 * @property string|null $name
 * @property string|null $src
 * @property int|null $cost
 * @property int|null $rent
 * @property int|null $rent_home1
 * @property int|null $rent_home2
 * @property int|null $rent_home3
 * @property int|null $rent_home4
 * @property int|null $rent_inn
 * @property int|null $homes_inn_cost
 */
class Property extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'property';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['cost', 'rent', 'rent_home1', 'rent_home2', 'rent_home3', 'rent_home4', 'rent_inn', 'homes_inn_cost'], 'integer'],
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
            'cost' => 'Cost',
            'rent' => 'Rent',
            'rent_home1' => 'Rent Home1',
            'rent_home2' => 'Rent Home2',
            'rent_home3' => 'Rent Home3',
            'rent_home4' => 'Rent Home4',
            'rent_inn' => 'Rent Inn',
            'homes_inn_cost' => 'Homes Inn Cost',
        ];
    }
}
