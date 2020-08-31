<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%game_status}}`.
 */
class m200823_143704_create_property_game_status_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%property_game_status}}', [
            'id' => $this->primaryKey(),
            'rent_state_id' => $this->integer(),
            'property_id' => $this->integer(),
            'player_id'=>$this->integer(),
            'game_session_id' => $this->integer(),
            'group_id'=>$this->integer(),
            'group_full'=>$this->boolean()
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%property_game_status}}');
    }
}
