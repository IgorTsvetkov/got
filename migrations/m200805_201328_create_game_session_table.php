<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%game_session}}`.
 */
class m200805_201328_create_game_session_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%game_session}}', [
            'id' => $this->primaryKey(),
            'name'=>$this->string(),
            'leader_user_id'=>$this->integer(),
            'turn_stage'=>$this->integer(),
            'turn_player_id'=>$this->integer(),
            'roll_count_first'=>$this->string(),
            'roll_count_second'=>$this->string(),
            'current_event_id'=>$this->integer(),
            'created_at' => $this->string(),
            'started_at'=>$this->string(),
            'finished_at'=>$this->string(),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%game_session}}');
    }
}
