<?php

/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace app\commands;

use yii\db\Query;
use app\models\Player;
use app\models\RentState;
use yii\console\Controller;

require_once __DIR__ . '/../vendor/autoload.php';
/**
 * This command echoes the first argument that you have entered.
 *
 * This command is provided as an example for you to learn how to create console commands.
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */

class TestController extends Controller
{
    function actionTest()
    {
        $players=Player::find()
        ->select(["COUNT(pgs.rent_state_id) as count,pgs.rent_state_id,rent_state.name"])
        ->where(["player.id"=>1])
        ->joinWith(["propertyGameStatuses as pgs"])
        ->where([">","rent_state_id",1])
        ->groupBy("pgs.rent_state_id")
        ->join("LEFT JOIN","rent_state","rent_state.id=pgs.rent_state_id1")
        ->asArray()
        ->all();

        var_dump($players);
    }
}
