<?php

namespace app\models\estate;

use Yii;
use app\models\Cell;
use app\models\estate\Estate;
use app\helpers\EstateTypeHelper;
use app\models\estate\Base\IEstate;

/**
 * This is the model class for table "utility".
 *
 * @property int $id
 * @property string|null $name
 * @property string|null $src
 * @property int $cost
 */
class Utility extends Estate
{
    public static function getEstateType()
    {
        return EstateTypeHelper::UTILITY;
    }
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
    public function getCell()
    {
        return $this->hasOne(Cell::class,["utility_id"=>"id"]);
    }
}
