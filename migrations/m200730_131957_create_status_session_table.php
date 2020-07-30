<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%status_session}}`.
 */
class m200730_131957_create_status_session_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%status_session}}', [
            'id' => $this->primaryKey(),
            'name' => $this->string(),
        ]);
        $this->insert("{{%status_session}}",["name"=>"in room"]);
        $this->insert("{{%status_session}}",["name"=>"active"]);
        $this->insert("{{%status_session}}",["name"=>"finished"]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%status_session}}');
    }
}
