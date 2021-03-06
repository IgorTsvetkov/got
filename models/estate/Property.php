<?php

namespace app\models\estate;

use app\helpers\EstateManager;
use app\helpers\EstateTypeHelper;
use Yii;
use app\models\Cell;
use app\models\estate\Estate;
use app\models\estate\Base\IEstate;
use app\models\gamestatus\CommonEstateGameStatus;
use app\models\Player;
use app\models\PropertyGroup;
use app\models\gamestatus\PropertyGameStatus;

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
class Property extends Estate
{
    /**
     * {@inheritdoc}
     */
    public static function getEstateType()
    {
        return EstateTypeHelper::PROPERTY;
    }
    public static function hasGroup()
    {
        return true;
    }
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
        return $this->hasMany(PropertyGameStatus::class,["estate_id"=>"id"]);
    }
    // public function getPropertyGameStatus(int $game_session_id)
    // {
    //     return $this->via("propertyGameStatuses")->where(["game_session_id"=>$game_session_id])->limit(1)->one();
    // }
    public function getCommonGameStatuses(){
        return $this->hasMany(CommonEstateGameStatus::class,["estate_id"=>"id"]);
    }
    public function getPlayerOwner(){
        return $this->hasOne(Player::class,["id"=>"player_id"])->via("commonGameStatuses");
    }
}
