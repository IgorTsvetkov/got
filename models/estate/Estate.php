<?php

namespace app\models\estate;

use Exception;
use yii\db\ActiveRecord;

/**
 * @property int $id
 * @property string|null $name
 * @property string|null $src
 * @property int $cost
 */

class Estate extends ActiveRecord
{
    public static function getEstateType(){
        throw new Exception("Not implemented");
    }
}
