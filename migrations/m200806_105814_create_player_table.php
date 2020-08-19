<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%player}}`.
 */
class m200806_105814_create_player_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%player}}', [
            'id' => $this->primaryKey(),
            "game_session_id"=>$this->integer(),
            "hero_id"=>$this->integer(),
            "user_id"=>$this->integer(),
            'slot' => $this->integer(),
            'position' => $this->integer(),
            'money'=>$this->integer(),
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
