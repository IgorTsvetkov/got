<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%auction}}`.
 */
class m200906_142358_create_auction_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%auction}}', [
            'id' => $this->primaryKey(),
            'game_session_id'=>$this->integer(),
            'target_type'=>$this->string(),
            "target_id"=>$this->integer(),
            'target_name'=>$this->string(),
            "cost"=>$this->integer(),
            "max_bet_player_id"=>$this->integer(),
            "turn_player_id"=>$this->integer(),
            "is_finished"=>$this->boolean(),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%auction}}');
    }
}
