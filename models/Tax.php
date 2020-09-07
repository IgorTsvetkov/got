<?php

namespace app\models;

use Yii;
use app\models\Cell;

/**
 * This is the model class for table "tax".
 *
 * @property int $id
 * @property string|null $name
 * @property string|null $src
 * @property int $cost
 * @property string|null $tax1
 * @property string|null $tax2
 * @property string|null $tax3
 * @property string|null $tax4
 */
class Tax extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'tax';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name', 'src', 'tax1', 'tax2', 'tax3', 'tax4'], 'string', 'max' => 255],
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
            'tax1' => 'Tax1',
            'tax2' => 'Tax2',
            'tax3' => 'Tax3',
            'tax4' => 'Tax4',
        ];
    }
    public function getTaxGameStatuses(){
        return $this->hasMany(TaxGameStatus::class,["tax_id"=>"id"]);
    }
    public function getCell()
    {
        return $this->hasOne(Cell::class,["tax_id"=>"id"]);
    }
}
