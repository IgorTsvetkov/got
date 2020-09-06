<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "tax_game_status".
 *
 * @property int $id
 * @property int|null $tax_id
 * @property int|null $player_id
 * @property int|null $game_session_id
 */
class TaxGameStatus extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'tax_game_status';
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
    public function getPlayer()
    {
        return $this->hasOne(Player::class,["id"=>"player_id"]);
    }
}
