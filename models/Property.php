<?php

namespace app\models;

use Yii;
use app\models\Player;
use app\models\PropertyGroup;
use Codeception\Lib\Generator\Group;

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
 * @property int|null $group_id
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
    public function extraFields()
    {
        return ['group'];
    }
    public function getGroup(){
        return $this->hasOne(PropertyGroup::class,['id'=>'group_id']);
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
    public function getCell()
    {
        return $this->hasOne(Cell::class,["property_id"=>"id"]);
    }
    public function getPropertyGameStatuses(){
        return $this->hasMany(PropertyGameStatus::class,["property_id"=>"id"]);
    }
    public function getPlayerOwner(){
        return $this->hasOne(Player::class,["id"=>"player_id"])->via("propertyGameStatuses");
    }
}
