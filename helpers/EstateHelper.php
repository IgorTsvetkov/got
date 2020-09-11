<?php 
namespace app\helpers;

use app\models\estate\Estate;
use app\models\estate\Base\IEstate;
use yii\base\Exception;
use app\models\estate\Tax;
use app\models\estate\Utility;
use app\models\estate\Property;

class EstateHelper{
    public static function getEstate($type_id,$id):Estate{
        $estate=null;
        switch ($type_id) {
            case EstateTypeHelper::PROPERTY:
                $estate=Property::find()->where(["id" => $id])->with(["cell", "group"])->one();
                break;
            case EstateTypeHelper::TAX:
                $estate=Tax::find()->where(["id"=>$id])->with("cell")->one();
                break;
            case EstateTypeHelper::UTILITY:
                $estate=Utility::find()->where(["id"=>$id])->with("cell")->one();
                break;
            default:
                throw new Exception("Can't handle the next type",$type_id);
                break;
        }
        return $estate;
    }
}