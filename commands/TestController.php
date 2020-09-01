<?php

/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace app\commands;

use app\helpers\SpiderEventHelper;
use yii\db\Query;
use app\models\Player;
use app\models\PropertyGameStatus;
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
        $damage_cost=SpiderEventHelper::calculateWildfireDamageCost(4);
        var_dump($damage_cost);
    }
}
