<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%tax}}`.
 */
class m200729_062537_create_tax_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%tax}}', [
            'id' => $this->primaryKey(),
            'name'=>$this->string(),
            'src'=>$this->string(),
            'tax1'=>$this->string(),
            'tax2'=>$this->string(),
            'tax3'=>$this->string(),
            'tax4'=>$this->string(),
        ]);
        $this->insert('{{%tax}}',[
            'id' =>1,
            'name'=>'lanister',
            'src'=>'web/images/cells/5.jpg',#TO DO Extract logo from tax card image
            'tax1'=>'25',
            'tax2'=>'50',
            'tax3'=>'100',
            'tax4'=>'200',
        ]);  
        $this->insert('{{%tax}}',[
            'id' =>2,
            'name'=>'targaryen',
            'src'=>'web/images/cells/14.jpg',#TO DO Extract logo from tax card image
            'tax1'=>'25',
            'tax2'=>'50',
            'tax3'=>'100',
            'tax4'=>'200',
        ]);
        $this->insert('{{%tax}}',[
            'id' =>3,
            'name'=>'stark',
            'src'=>'web/images/cells/25.jpg',#TO DO Extract logo from tax card image
            'tax1'=>'25',
            'tax2'=>'50',
            'tax3'=>'100',
            'tax4'=>'200',
        ]);

        $this->insert('{{%tax}}',[
            'id' =>4,
            'name'=>'baratheon',
            'src'=>'web/images/cells/34.jpg',#TO DO Extract logo from tax card image
            'tax1'=>'25',
            'tax2'=>'50',
            'tax3'=>'100',
            'tax4'=>'200',
        ]);

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%tax}}');
    }
}
