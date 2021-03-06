<?php

/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace app\commands;

use app\models\Auction;
use app\models\Cell;
use yii\console\Controller;
use app\models\gamestatus\CommonEstateGameStatus;
use app\models\gamestatus\TaxGameStatus;

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
        $x=new Auction();
        $a=$x->getAttributes();
        echo $a["xx"];
    }
}
