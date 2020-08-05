<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%event}}`.
 */
class m200730_095633_create_event_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%event}}', [
            'id' => $this->primaryKey(),
            "name"=>$this->string(),
            "src"=>$this->string(),
        ]);

        $this->insert('{{%event}}',[
            "id"=>1,
            "name"=>"inn at the crossroads",
            "src"=>"/images/cells/0.jpg",
        ]);
        $this->insert('{{%event}}',[
            "id"=>2,
            "name"=>"bird",#vertical
            "src"=>"/images/cells/2.jpg",
        ]);
        $this->insert('{{%event}}',[
            "id"=>3,
            "name"=>"spider",#vertical
            "src"=>"/images/cells/12.jpg",
        ]);
        $this->insert('{{%event}}',[
            "id"=>4,
            "name"=>"take the black",
            "src"=>"/images/cells/15.jpg",
        ]);
        $this->insert('{{%event}}',[
            "id"=>5,
            "name"=>"spider",#horizontal
            "src"=>"/images/cells/20.jpg",
        ]);
        $this->insert('{{%event}}',[
            "id"=>6,
            "name"=>"master of coin",
            "src"=>"/images/cells/21.jpg",
        ]);
        $this->insert('{{%event}}',[
            "id"=>7,
            "name"=>"just visiting",
            "src"=>"/images/cells/24.jpg",
        ]);
        $this->insert('{{%event}}',[
            "id"=>8,
            "name"=>"iron bank",
            "src"=>"/images/cells/35.jpg",
        ]);
        $this->insert('{{%event}}',[
            "id"=>9,
            "name"=>"still alive",
            "src"=>"/images/cells/39.jpg",
        ]);
        $this->insert('{{%event}}',[
            "id"=>10,
            "name"=>"bird",#horizontal
            "src"=>"/images/cells/17.jpg",
        ]);

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%event}}');
    }
}
