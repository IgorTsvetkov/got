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
            "src"=>"/images/cells/0",
        ]);
        $this->insert('{{%event}}',[
            "id"=>2,
            "name"=>"bird vertical",
            "src"=>"/images/cells/2",
        ]);
        $this->insert('{{%event}}',[
            "id"=>3,
            "name"=>"lanister",
            "src"=>"/images/cells/5",
        ]);
        $this->insert('{{%event}}',[
            "id"=>4,
            "name"=>"the trident",
            "src"=>"/images/cells/8",
        ]);

        $this->insert('{{%event}}',[
            "id"=>5,
            "name"=>"spider vertical",
            "src"=>"/images/cells/12",
        ]);
        $this->insert('{{%event}}',[
            "id"=>6,
            "name"=>"targarien",
            "src"=>"/images/cells/14",

        ]);
        $this->insert('{{%event}}',[
            "id"=>7,
            "name"=>"take the black",
            "src"=>"/images/cells/15",
        ]);
        $this->insert('{{%event}}',[
            "id"=>8,
            "name"=>"spider horizontal",
            "src"=>"/images/cells/20",
        ]);
        $this->insert('{{%event}}',[
            "id"=>9,
            "name"=>"master of coin",
            "src"=>"/images/cells/21",
        ]);
        $this->insert('{{%event}}',[
            "id"=>10,
            "name"=>"just visiting",
            "src"=>"/images/cells/24",

        ]);        
        $this->insert('{{%event}}',[
            "id"=>11,
            "name"=>"stark",
            "src"=>"/images/cells/25",

        ]);
        $this->insert('{{%event}}',[
            "id"=>12,
            "name"=>"the kingsroad",
            "src"=>"/images/cells/28",
        ]);

        $this->insert('{{%event}}',[
            "id"=>13,
            "name"=>"baratheon",
            "src"=>"/images/cells/34",
        ]);
        $this->insert('{{%event}}',[
            "id"=>14,
            "name"=>"iron bank",
            "src"=>"/images/cells/35",
        ]);
        $this->insert('{{%event}}',[
            "id"=>15,
            "name"=>"still alive",
            "src"=>"/images/cells/39",
        ]);
        $this->insert('{{%event}}',[
            "id"=>16,
            "name"=>"bird horizontal",
            "src"=>"/images/cells/17",
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
