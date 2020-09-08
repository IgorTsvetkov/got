<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%common_game_status}}`.
 */
class m200908_052006_create_common_game_status_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%common_game_status}}', [
            'id' => $this->primaryKey(),
            'cell_id'=>$this->integer(),
            'game_session_id' => $this->integer(),
            'player_id'=>$this->integer(),
            'estate_type_id'=>$this->integer(),
            'estate_id'=>$this->integer(),
            'rent_state_id' => $this->integer(),
            'group_id'=>$this->integer(),
            'is_group_full'=>$this->boolean(),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%common_game_status}}');
    }
}
