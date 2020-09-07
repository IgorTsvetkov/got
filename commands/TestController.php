<?php

/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace app\commands;

use Exception;
use yii\db\Query;
use app\models\Tax;
use app\models\Player;
use app\models\Utility;
use app\models\Property;
use app\models\RentState;
use yii\console\Controller;
use app\models\TaxGameStatus;
use app\models\UtilityGameStatus;
use app\helpers\spyderEventHelper;
use app\models\PropertyGameStatus;

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
        $queryCellsId=(new Query)->select("cell_id,player_id")->from(PropertyGameStatus::tableName())
            ->union((new Query())->select("cell_id,player_id")->from(TaxGameStatus::tableName()))
            ->union((new Query())->select("cell_id,player_id")->from(UtilityGameStatus::tableName()));
        $player=Player::find()->select("*")->addSelect("queryCellsId.*")->where(["id"=>5])->leftJoin(["queryCellsId"=>$queryCellsId],"player.id=queryCellsId.player_id")->asArray()->one();
        var_dump($player);
    }
    public static function getTypedModel($type, $id,$loadGameStatus=false)
    {
        $typedModel = null;
        switch ($type) {
            case 'property':
                $typedModel = Property::find();
                break;
            case 'tax':
                $typedModel = Tax::find();
                break;
            case 'utility':
                $typedModel = Utility::find();
                break;
            default:
                throw new Exception("Cand start Auction for " . $type);
                break;
        }
        $typedModel->where(["id"=>$id]);
        // if($loadGameStatus)
        //     $typedModel->with($type."GameStatus");
        $typedModel->one();
        return $typedModel;
    }
}
