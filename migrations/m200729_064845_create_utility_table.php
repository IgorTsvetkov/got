<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%utility}}`.
 */
class m200729_064845_create_utility_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%utility}}', [
            'id' => $this->primaryKey(),
            'name' => $this->string(),
            'src' => $this->string(),
            'cost'=>$this->integer(),
        ]);
        $this->insert('{{%utility}}', [
            'id' => 1,
            'name' =>'the trident',
            'src' =>'/web/images/cells/8.jpg',
            'cost'=>150,
        ]);
        $this->insert('{{%utility}}', [
            'id' => 2,
            'name' =>'the kingsroad',
            'src' =>'/web/images/cells/28.jpg',
            'cost'=>150 
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%utility}}');
    }
}
