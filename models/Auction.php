<?php

namespace app\models;

use app\helpers\YesNo;
use Yii;

/**
 * This is the model class for table "auction".
 *
 * @property int $id
 * @property int|null $game_session_id
 * @property string|null $estate_name
 * @property string|null $estate_type_id
 * @property int|null $estate_id
 * @property int|null $cost
 * @property int|null $turn_player_id
 * @property int|null $max_bet_player_id
 * @property int|null $is_finished
 */
class Auction extends \yii\db\ActiveRecord
{
    public function finishAuction(){
        $this->is_finished=YesNo::YES;
    }
    public function isMaxBetPlayer(Player $player):bool
    {
        return $this->max_bet_player_id==$player->id;
    }
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
        return $this->hasMany(Player::class,["game_session_id"=>"id"])->via("gameSession")->where(["in_auction"=>YesNo::YES]);
    }
    public function getAuctionTurnPlayer()
    {
        return $this->hasOne(Player::class,["id"=>"turn_player_id"]);
    }
    public function getMaxBetPlayer()
    {
        return $this->hasOne(Player::class,["id"=>"max_bet_player_id"]);
    }
    
}
