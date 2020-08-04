<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%cell}}`.
 */
class m200729_084531_create_cell_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%cell}}', [
            'id' => $this->primaryKey(),
            'position'=>$this->integer(),
            'event_id' => $this->string(),
            'castle_id' => $this->string(),
        ]);
        $this->insert('{{%cell}}',[
            'position'=>"0",
            'event_id' =>"",
            'castle_id' =>"1",
            ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%cell}}');
    }
}
