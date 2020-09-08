<?php

namespace app\models\gamestatus;

use Yii;

/**
 * This is the model class for table "utility_game_status".
 *
 * @property int $id
 * @property int|null $cell_id
 * @property int|null $estate_id
 * @property int|null $estate_type_id
 * @property int|null $player_id
 * @property int|null $game_session_id
 * 
 */
class UtilityGameStatus extends CommonGameStatus
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['utility_id', 'player_id', 'game_session_id'], 'integer'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'utility_id' => 'Utility ID',
            'player_id' => 'Player ID',
            'game_session_id' => 'Game Session ID',
        ];
    }
}
