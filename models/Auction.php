<?php

namespace app\models;

use app\helpers\YesNo;
use Yii;

/**
 * This is the model class for table "auction".
 *
 * @property int $id
 * @property int|null $game_session_id
 * @property string|null $target_type
 * @property string|null $target_name
 * @property int|null $target_id
 * @property int|null $cost
 * @property int|null $turn_player_id
 */
class Auction extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'auction';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['game_session_id', 'target_id', 'turn_player_id'], 'integer'],
            [['target_type'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'game_session_id' => 'Game Session ID',
            'target_type' => 'Cell Sale Type',
            'target_id' => 'Cell Sale ID',
            'turn_player_id' => 'Turn Player ID',
        ];
    }
    public function getGameSession()
    {
        return $this->hasOne(GameSession::class,["id"=>"game_session_id"]);
    }
    public function getActivePlayers()
    {
        return $this->hasMany(Player::class,["game_session_id"=>"id"])->via("gameSession")->where(["in_action"=>YesNo::YES]);
    }
    
}
