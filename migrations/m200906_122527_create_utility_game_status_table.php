<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%utility_game_status}}`.
 */
class m200906_122527_create_utility_game_status_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%utility_game_status}}', [
            'id' => $this->primaryKey(),
            'utility_id' => $this->integer(),
            'player_id' => $this->integer(),
            'game_session_id' => $this->integer(),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%utility_game_status}}');
    }
}
