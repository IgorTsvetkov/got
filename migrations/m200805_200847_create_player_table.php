<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%player}}`.
 */
class m200805_200847_create_player_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%player}}', [
            'id' => $this->primaryKey(),
            'session_id' => $this->integer(),
            'user_id' => $this->string(),
            'position' => $this->integer(),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%player}}');
    }
}
