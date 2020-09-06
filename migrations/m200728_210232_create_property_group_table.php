<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%property_group}}`.
 */
class m200728_210232_create_property_group_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%property_group}}', [
            'id' => $this->primaryKey(),
            'color_name'=>$this->string(),
            'count_max'=>$this->string(),
        ]);
        $this->insert('{{%property_group}}',['id'=>1,'color_name'=>'red','count_max'=>3]);
        $this->insert('{{%property_group}}',['id'=>2,'color_name'=>'yellow','count_max'=>3]);
        $this->insert('{{%property_group}}',['id'=>3,'color_name'=>'green','count_max'=>3]);
        $this->insert('{{%property_group}}',['id'=>4,'color_name'=>'dark_purple','count_max'=>2]);
        $this->insert('{{%property_group}}',['id'=>5,'color_name'=>'brown','count_max'=>2]);
        $this->insert('{{%property_group}}',['id'=>6,'color_name'=>'dark_blue','count_max'=>3]);
        $this->insert('{{%property_group}}',['id'=>7,'color_name'=>'light_blue','count_max'=>3]);
        $this->insert('{{%property_group}}',['id'=>8,'color_name'=>'orange','count_max'=>3]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%property_group}}');
    }
}
