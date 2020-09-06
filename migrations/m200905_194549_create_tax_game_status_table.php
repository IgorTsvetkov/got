<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%tax_game_status}}`.
 */
class m200905_194549_create_tax_game_status_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%tax_game_status}}', [
            'id' => $this->primaryKey(),
            'tax_id'=>$this->integer(),
            'player_id'=>$this->integer(),
            'game_session_id'=>$this->integer(),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%tax_game_status}}');
    }
}
