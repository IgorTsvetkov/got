<?php

namespace app\models\gamestatus;

use app\helpers\IPayRent;
use Yii;
use app\models\Player;

/**
 * This is the model class for table "tax_game_status".
 *
 * @property int $id
 * @property int|null $cell_id
 * @property int|null $estate_id
 * @property int|null $estate_type_id
 * @property int|null $player_id
 * @property int|null $game_session_id
 * 
 */
class TaxGameStatus extends CommonGameStatus implements IPayRent
{
    public function getRentCost(int $estate_id, int $estate_type_id, int $player_to_id): int
    {
        $taxGameStatus = self::find()
        ->select("concat('tax',count('estate_type_id'))")
        ->where(["estate_type_id" => $estate_type_id, "player_id" => $player_to_id])
        ->with(["tax"])
        ->limit(1)
        ->one();
    }
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['tax_id', 'tax_level', 'player_id', 'game_session_id'], 'integer'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'tax_id' => 'Tax ID',
            'tax_level' => 'Tax Level',
            'player_id' => 'Player ID',
            'game_session_id' => 'Game Session ID',
        ];
    }
}
