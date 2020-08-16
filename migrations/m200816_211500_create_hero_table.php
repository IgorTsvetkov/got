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
        ]);

        $this->batchInsert('{{%hero}}',['src','name'],
            [ 
                ["/web/images/figurines/figure0.png","Faceless men"],
                ["/web/images/figurines/figure1.png","Stannis Baratheon"],
                ["/web/images/figurines/figure2.png","Robert Baratheon"],
                ["/web/images/figurines/figure3.png","Daenerys Targaryen"],
                ["/web/images/figurines/figure4.png","Tyrion Lannister"],
                ["/web/images/figurines/figure5.png","Jaime Lannister"],
                ["/web/images/figurines/figure6.png","Robb Stark"],
                ["/web/images/figurines/figure7.png","Jon Show"],
                ["/web/images/figurines/figure8.png","Cersei Lannister"],
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
