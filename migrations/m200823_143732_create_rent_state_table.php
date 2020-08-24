<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%rent_state}}`.
 */
class m200823_143732_create_rent_state_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%rent_state}}', [
            'id' => $this->primaryKey(),
            'name' => $this->string(),
        ]);
        $this->batchInsert('{{%rent_state}}',['name'],
        [ 
            ["rent"],
            ["rent_home1"],
            ["rent_home2"],
            ["rent_home3"],
            ["rent_home4"],
            ["rent_home5"],
            ["rent_inn"],
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%rent_state}}');
    }
}
