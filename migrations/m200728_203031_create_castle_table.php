<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%castle}}`.
 */
class m200728_203031_create_castle_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%castle}}', [
            'id' => $this->primaryKey(),
            'name' => $this->string(),
            'src'=>$this->string(),
            'cost' => $this->integer(),
            'rent' => $this->integer(),
            'rent_home1' => $this->integer(),
            'rent_home2' => $this->integer(),
            'rent_home3' => $this->integer(),
            'rent_home4' => $this->integer(),
            'rent_inn' => $this->integer(),
            'homes_inn_cost' => $this->integer(),
            // 'group_id'=>$this->integer()
        ]);
        $this->insert('{{%castle}}',[
            'id'=>1,
            'name' => "harrenhall",
            'src'=>"/images/t-1",
            'cost' =>"220",
            'rent' =>"18" ,
            'rent_home1' => "90",
            'rent_home2' => "250",
            'rent_home3' => "700",
            'rent_home4' => "875",
            'rent_inn' => "1050",
            'homes_inn_cost' => "150",
        ]);
        // $this->insert('{{%castle}}',[
        //     "id"=>2,
        //     'name' => "lannisport",
        //     'cost' =>"220",
        //     'rent' =>"18" ,
        //     'rent_home1' => "90",
        //     'rent_home2' => "250",
        //     'rent_home3' => "700",
        //     'rent_home4' => "875",
        //     'rent_inn' => "1050",
        //     'homes_inn_cost' => "150",
        // ]);
        // $this->insert('{{%castle}}',[
        //     "id"=>3,
        //     'name' => "casterly rock",
        //     'cost' =>"240",
        //     'rent' =>"20" ,
        //     'rent_home1' => "100",
        //     'rent_home2' => "300",
        //     'rent_home3' => "750",
        //     'rent_home4' => "925",
        //     'rent_inn' => "1100",
        //     'homes_inn_cost' => "150",
        // ]);
        //groups???????
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%castle}}');
    }
}
