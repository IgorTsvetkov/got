<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%event}}`.
 */
class m200729_081633_create_event_table extends Migration
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
            "src"=>"/web/images/cells/0.jpg",
        ]);
        $this->insert('{{%event}}',[
            "id"=>2,
            "name"=>"bird",#vertical
            "src"=>"/web/images/cells/2.jpg",
        ]);
        $this->insert('{{%event}}',[
            "id"=>3,
            "name"=>"spider",#vertical
            "src"=>"/web/images/cells/12.jpg",
        ]);
        $this->insert('{{%event}}',[
            "id"=>4,
            "name"=>"take the black",
            "src"=>"/web/images/cells/15.jpg",
        ]);
        $this->insert('{{%event}}',[
            "id"=>5,
            "name"=>"spider",#horizontal
            "src"=>"/web/images/cells/20.jpg",
        ]);
        $this->insert('{{%event}}',[
            "id"=>6,
            "name"=>"master of coin",
            "src"=>"/web/images/cells/21.jpg",
        ]);
        $this->insert('{{%event}}',[
            "id"=>7,
            "name"=>"just visiting",
            "src"=>"/web/images/cells/24.jpg",
        ]);
        $this->insert('{{%event}}',[
            "id"=>8,
            "name"=>"iron bank",
            "src"=>"/web/images/cells/35.jpg",
        ]);
        $this->insert('{{%event}}',[
            "id"=>9,
            "name"=>"still alive",
            "src"=>"/web/images/cells/39.jpg",
        ]);
        $this->insert('{{%event}}',[
            "id"=>10,
            "name"=>"bird",#horizontal
            "src"=>"/web/images/cells/17.jpg",
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
