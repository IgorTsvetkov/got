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
        ]);
        $this->insert('{{%event}}',[
            "name"=>"inn at the crossroads",
        ]);
        $this->insert('{{%event}}',[
            "name"=>"bird",
        ]);
        $this->insert('{{%event}}',[
            "name"=>"targarien",
        ]);
        $this->insert('{{%event}}',[
            "name"=>"the trident",
        ]);
        $this->insert('{{%event}}',[
            "name"=>"take the black",
        ]);
        $this->insert('{{%event}}',[
            "name"=>"spider",
        ]);
        $this->insert('{{%event}}',[
            "name"=>"master of coin",
        ]);
        $this->insert('{{%event}}',[
            "name"=>"still alive",
        ]);
        $this->insert('{{%event}}',[
            "name"=>"iron bank",
        ]);
        $this->insert('{{%event}}',[
            "name"=>"baratheon",
        ]);
        $this->insert('{{%event}}',[
            "name"=>"just visiting",
        ]);
        $this->insert('{{%event}}',[
            "name"=>"the kingsroad",
        ]);
        $this->insert('{{%event}}',[
            "name"=>"stark",
        ]);


        $this->insert('{{%event}}',[
            "name"=>"spider",
            "event_id"=>""
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
