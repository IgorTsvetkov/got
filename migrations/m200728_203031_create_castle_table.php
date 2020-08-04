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
            'name' => "harrenhall",
            'src'=>"/images/cells/1",
            'cost' =>"220",
            'rent' =>"18" ,
            'rent_home1' => "90",
            'rent_home2' => "250",
            'rent_home3' => "700",
            'rent_home4' => "875",
            'rent_inn' => "1050",
            'homes_inn_cost' => "150",
        ]);
        $this->insert('{{%castle}}',[
            'name' => "lannisport",
            'src'=>"/images/cells/3",
            'cost' =>"220",
            'rent' =>"18" ,
            'rent_home1' => "90",
            'rent_home2' => "250",
            'rent_home3' => "700",
            'rent_home4' => "875",
            'rent_inn' => "1050",
            'homes_inn_cost' => "150",
        ]);
        $this->insert('{{%castle}}',[
            'name' => "casterly rock",
            'src'=>"/images/cells/4",
            'cost' =>"240",
            'rent' =>"20" ,
            'rent_home1' => "100",
            'rent_home2' => "300",
            'rent_home3' => "750",
            'rent_home4' => "925",
            'rent_inn' => "1100",
            'homes_inn_cost' => "150",
        ]);
        $this->insert('{{%castle}}',[
            'name' => "astapor",
            'src'=>"/images/cells/6",
            'cost' =>"260",
            'rent' =>"22" ,
            'rent_home1' => "110",
            'rent_home2' => "330",
            'rent_home3' => "800",
            'rent_home4' => "975",
            'rent_inn' => "1150",
            'homes_inn_cost' => "150",
        ]);
        $this->insert('{{%castle}}',[
            'name' => "yunkai",
            'src'=>"/images/cells/7",
            'cost' =>"260",
            'rent' =>"22" ,
            'rent_home1' => "110",
            'rent_home2' => "330",
            'rent_home3' => "800",
            'rent_home4' => "975",
            'rent_inn' => "1150",
            'homes_inn_cost' => "150",
        ]);
        $this->insert('{{%castle}}',[
            'name' => "meereen",            
            'src'=>"/images/cells/9",
            'cost' =>"280",
            'rent' =>"24" ,
            'rent_home1' => "120",
            'rent_home2' => "360",
            'rent_home3' => "850",
            'rent_home4' => "1025",
            'rent_inn' => "1200",
            'homes_inn_cost' => "150",
        ]);
        
        $this->insert('{{%castle}}',[
            'name' => "horn hill",
            'src'=>"/images/cells/10",
            'cost' =>"300",
            'rent' =>"26" ,
            'rent_home1' => "130",
            'rent_home2' => "390",
            'rent_home3' => "900",
            'rent_home4' => "1100",
            'rent_inn' => "1275",
            'homes_inn_cost' => "200",
        ]);
        $this->insert('{{%castle}}',[
            'name' => "highgarden",
            'src'=>"/images/cells/11",
            'cost' =>"300",
            'rent' =>"26" ,
            'rent_home1' => "130",
            'rent_home2' => "390",
            'rent_home3' => "900",
            'rent_home4' => "1100",
            'rent_inn' => "1275",
            'homes_inn_cost' => "200",
        ]);
        $this->insert('{{%castle}}',[
            'name' => "oldtown",
            'src'=>"/images/cells/13",
            'cost' =>"320",
            'rent' =>"28" ,
            'rent_home1' => "150",
            'rent_home2' => "450",
            'rent_home3' => "1000",
            'rent_home4' => "1200",
            'rent_inn' => "1400",
            'homes_inn_cost' => "200",
        ]);
        $this->insert('{{%castle}}',[
            'name' => "bravos",
            'src'=>"/images/cells/16",
            'cost' =>"200",
            'rent' =>"16" ,
            'rent_home1' => "80",
            'rent_home2' => "220",
            'rent_home3' => "600",
            'rent_home4' => "800",
            'rent_inn' => "1000",
            'homes_inn_cost' => "100",
        ]);
        $this->insert('{{%castle}}',[
            'name' => "qarth",
            'src'=>"/images/cells/18",
            'cost' =>"180",
            'rent' =>"14" ,
            'rent_home1' => "70",
            'rent_home2' => "200",
            'rent_home3' => "550",
            'rent_home4' => "750",
            'rent_inn' => "950",
            'homes_inn_cost' => "100",
        ]);
        $this->insert('{{%castle}}',[
            'name' => "dragonstone",
            'src'=>"/images/cells/19",
            'cost' =>"350",
            'rent' =>"35" ,
            'rent_home1' => "175",
            'rent_home2' => "500",
            'rent_home3' => "1100",
            'rent_home4' => "1300",
            'rent_inn' => "1500",
            'homes_inn_cost' => "200",
        ]);
        $this->insert('{{%castle}}',[
            'name' => "volantis",
            'src'=>"/images/cells/22",
            'cost' =>"180",
            'rent' =>"14" ,
            'rent_home1' => "70",
            'rent_home2' => "200",
            'rent_home3' => "550",
            'rent_home4' => "750",
            'rent_inn' => "950",
            'homes_inn_cost' => "100",
        ]);
        $this->insert('{{%castle}}',[
            'name' => "king's landing",
            'src'=>"/images/cells/23",
            'cost' =>"400",
            'rent' =>"50" ,
            'rent_home1' => "200",
            'rent_home2' => "600",
            'rent_home3' => "1400",
            'rent_home4' => "1700",
            'rent_inn' => "2000",
            'homes_inn_cost' => "200",
        ]);
        $this->insert('{{%castle}}',[
            'name' => "the eyrie",
            'src'=>"/images/cells/26",
            'cost' =>"160",
            'rent' =>"12" ,
            'rent_home1' => "60",
            'rent_home2' => "180",
            'rent_home3' => "500",
            'rent_home4' => "700",
            'rent_inn' => "900",
            'homes_inn_cost' => "100",
        ]);
        $this->insert('{{%castle}}',[
            'name' => "riverrun",
            'src'=>"/images/cells/27",
            'cost' =>"140",
            'rent' =>"10" ,
            'rent_home1' => "50",
            'rent_home2' => "150",
            'rent_home3' => "450",
            'rent_home4' => "625",
            'rent_inn' => "750",
            'homes_inn_cost' => "100",
        ]);
        $this->insert('{{%castle}}',[
            'name' => "the twins",
            'src'=>"/images/cells/29",
            'cost' =>"140",
            'rent' =>"10" ,
            'rent_home1' => "50",
            'rent_home2' => "150",
            'rent_home3' => "450",
            'rent_home4' => "625",
            'rent_inn' => "750",
            'homes_inn_cost' => "100",
        ]);
        $this->insert('{{%castle}}',[
            'name' => "winterfell",
            'src'=>"/images/cells/30",
            'cost' =>"120",
            'rent' =>"8" ,
            'rent_home1' => "40",
            'rent_home2' => "100",
            'rent_home3' => "300",
            'rent_home4' => "450",
            'rent_inn' => "650",
            'homes_inn_cost' => "50",
        ]);
        $this->insert('{{%castle}}',[
            'name' => "the wall",
            'src'=>"/images/cells/31",
            'cost' =>"100",
            'rent' =>"6" ,
            'rent_home1' => "30",
            'rent_home2' => "90",
            'rent_home3' => "270",
            'rent_home4' => "400",
            'rent_inn' => "550",
            'homes_inn_cost' => "50",
        ]);
        $this->insert('{{%castle}}',[
            'name' => "pyke",
            'src'=>"/images/cells/33",
            'cost' =>"100",
            'rent' =>"6" ,
            'rent_home1' => "30",
            'rent_home2' => "90",
            'rent_home3' => "270",
            'rent_home4' => "400",
            'rent_inn' => "550",
            'homes_inn_cost' => "50",
        ]);
        $this->insert('{{%castle}}',[
            'name' => "sunspear",
            'src'=>"/images/cells/36",
            'cost' =>"60",
            'rent' =>"4" ,
            'rent_home1' => "20",
            'rent_home2' => "80",
            'rent_home3' => "180",
            'rent_home4' => "320",
            'rent_inn' => "450",
            'homes_inn_cost' => "50",
        ]);
        $this->insert('{{%castle}}',[
            'name' => "tower of joy",
            'src'=>"/images/cells/38",
            'cost' =>"60",
            'rent' =>"2" ,
            'rent_home1' => "10",
            'rent_home2' => "30",
            'rent_home3' => "90",
            'rent_home4' => "160",
            'rent_inn' => "250",
            'homes_inn_cost' => "50",
        ]);


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
