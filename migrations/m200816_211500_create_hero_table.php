<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%hero}}`.
 */
class m200816_211500_create_hero_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%hero}}', [
            'id' => $this->primaryKey(),
            'name' => $this->string(),
            'src' => $this->string(),
            'color'=>$this->string(),
        ]);

        $this->batchInsert('{{%hero}}',['src','name','color'],
            [ 
                ['/web/images/figurines/figure0.png','Faceless','grey'],
                ['/web/images/figurines/figure1.png','Stannis Baratheon','#a97947'],
                ['/web/images/figurines/figure2.png','Robert Baratheon','#f0e634'],
                ['/web/images/figurines/figure3.png','Daenerys Targaryen','#e33f7b'],
                ['/web/images/figurines/figure4.png','Tyrion Lannister','#5d3ae5'],
                ['/web/images/figurines/figure5.png','Jaime Lannister','#e02718'],
                ['/web/images/figurines/figure6.png','Robb Stark','#78b849'],
                ['/web/images/figurines/figure7.png','Jon Show','#fca92a'],
                ['/web/images/figurines/figure8.png','Cersei Lannister','#23c8f9'],
            ]
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%hero}}');
    }
}
