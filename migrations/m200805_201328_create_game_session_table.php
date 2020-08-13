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
