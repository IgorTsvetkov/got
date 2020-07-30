<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%castle_owner}}`.
 */
class m200730_080237_create_castle_owner_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%castle_owner}}', [
            'id' => $this->primaryKey(),
            'name'=>$this->name(),
            'color_name'=>$this->string(),
        ]);
        $this->insert('{{%castle_owner}}',["color_name"=>"red"]);
        $this->insert('{{%castle_owner}}',["color_name"=>"yellow"]);
        $this->insert('{{%castle_owner}}',["color_name"=>"green"]);
        $this->insert('{{%castle_owner}}',["color_name"=>"yellow"]);
        $this->insert('{{%castle_owner}}',["color_name"=>"brown"]);
        $this->insert('{{%castle_owner}}',["color_name"=>"dark_nlue"]);
        $this->insert('{{%castle_owner}}',["color_name"=>"light_blue"]);
        $this->insert('{{%castle_owner}}',["color_name"=>"orange"]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%castle_owner}}');
    }
}
