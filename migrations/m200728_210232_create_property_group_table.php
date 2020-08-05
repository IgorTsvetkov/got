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
        ]);
        $this->insert('{{%property_group}}',["id"=>1,"color_name"=>"red"]);
        $this->insert('{{%property_group}}',["id"=>2,"color_name"=>"yellow"]);
        $this->insert('{{%property_group}}',["id"=>3,"color_name"=>"green"]);
        $this->insert('{{%property_group}}',["id"=>4,"color_name"=>"dark purple"]);
        $this->insert('{{%property_group}}',["id"=>5,"color_name"=>"brown"]);
        $this->insert('{{%property_group}}',["id"=>6,"color_name"=>"dark_blue"]);
        $this->insert('{{%property_group}}',["id"=>7,"color_name"=>"light_blue"]);
        $this->insert('{{%property_group}}',["id"=>8,"color_name"=>"orange"]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%property_group}}');
    }
}
