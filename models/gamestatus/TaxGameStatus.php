<?php

namespace app\models\gamestatus;

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
class TaxGameStatus extends CommonGameStatus
{
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
