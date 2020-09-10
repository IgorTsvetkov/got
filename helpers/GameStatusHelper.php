<?php

namespace app\helpers;

use app\helpers\IPayRent;
use app\models\gamestatus\PropertyGameStatus;
use app\models\gamestatus\TaxGameStatus;
use app\models\gamestatus\UtilityGameStatus;

class GameStatusHelper
{
    public static function getClassByType($type_id): ?IPayRent
    {
        $buildingGameStatus = null;
        switch ($type_id) {
            case EstateTypeHelper::PROPERTY:
                $buildingGameStatus = new PropertyGameStatus();
                break;
            case EstateTypeHelper::UTILITY:
                $buildingGameStatus = new UtilityGameStatus();
                break;
            case EstateTypeHelper::TAX:
                $buildingGameStatus = new TaxGameStatus();
                break;
        }
        return $buildingGameStatus;
    }
}
