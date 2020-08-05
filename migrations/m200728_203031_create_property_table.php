<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%property}}`.
 */
class m200728_203031_create_property_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%property}}', [
            'id' => $this->primaryKey(),
            'name' => $this->string(),
            'src' => $this->string(),
            'cost' => $this->integer(),
            'rent' => $this->integer(),
            'rent_home1' => $this->integer(),
            'rent_home2' => $this->integer(),
            'rent_home3' => $this->integer(),
            'rent_home4' => $this->integer(),
            'rent_inn' => $this->integer(),
            'homes_inn_cost' => $this->integer(),
            'group_id' => $this->integer(),
        ]);
        $this->insert('{{%property}}', [
            'id' => 1,
            'name' => "harrenhall",
            'src' => "/web/images/cells/1.jpg",
            'cost' => "220",
            'rent' => "18",
            'rent_home1' => "90",
            'rent_home2' => "250",
            'rent_home3' => "700",
            'rent_home4' => "875",
            'rent_inn' => "1050",
            'homes_inn_cost' => "150",
            'group_id' => 1,
        ]);
        $this->insert('{{%property}}', [
            'id' => 2,
            'name' => "lannisport",
            'src' => "/web/images/cells/3.jpg",
            'cost' => "220",
            'rent' => "18",
            'rent_home1' => "90",
            'rent_home2' => "250",
            'rent_home3' => "700",
            'rent_home4' => "875",
            'rent_inn' => "1050",
            'homes_inn_cost' => "150",
            'group_id' => 1,
        ]);
        $this->insert('{{%property}}', [
            'id' => 3,
            'name' => "casterly rock",
            'src' => "/web/images/cells/4.jpg",
            'cost' => "240",
            'rent' => "20",
            'rent_home1' => "100",
            'rent_home2' => "300",
            'rent_home3' => "750",
            'rent_home4' => "925",
            'rent_inn' => "1100",
            'homes_inn_cost' => "150",
            'group_id' => 1,
        ]);
        $this->insert('{{%property}}', [
            'id' => 4,
            'name' => "astapor",
            'src' => "/web/images/cells/6.jpg",
            'cost' => "260",
            'rent' => "22",
            'rent_home1' => "110",
            'rent_home2' => "330",
            'rent_home3' => "800",
            'rent_home4' => "975",
            'rent_inn' => "1150",
            'homes_inn_cost' => "150",
            'group_id' => 2,
        ]);
        $this->insert('{{%property}}', [
            'id' => 5,
            'name' => "yunkai",
            'src' => "/web/images/cells/7.jpg",
            'cost' => "260",
            'rent' => "22",
            'rent_home1' => "110",
            'rent_home2' => "330",
            'rent_home3' => "800",
            'rent_home4' => "975",
            'rent_inn' => "1150",
            'homes_inn_cost' => "150",
            'group_id' => 2,
        ]);
        $this->insert('{{%property}}', [
            'id' => 6,
            'name' => "meereen",
            'src' => "/web/images/cells/9.jpg",
            'cost' => "280",
            'rent' => "24",
            'rent_home1' => "120",
            'rent_home2' => "360",
            'rent_home3' => "850",
            'rent_home4' => "1025",
            'rent_inn' => "1200",
            'homes_inn_cost' => "150",
            'group_id' => 2,
        ]);

        $this->insert('{{%property}}', [
            'id' => 7,
            'name' => "horn hill",
            'src' => "/web/images/cells/10.jpg",
            'cost' => "300",
            'rent' => "26",
            'rent_home1' => "130",
            'rent_home2' => "390",
            'rent_home3' => "900",
            'rent_home4' => "1100",
            'rent_inn' => "1275",
            'homes_inn_cost' => "200",
            'group_id' => 3,
        ]);
        $this->insert('{{%property}}', [
            'id' => 8,
            'name' => "highgarden",
            'src' => "/web/images/cells/11.jpg",
            'cost' => "300",
            'rent' => "26",
            'rent_home1' => "130",
            'rent_home2' => "390",
            'rent_home3' => "900",
            'rent_home4' => "1100",
            'rent_inn' => "1275",
            'homes_inn_cost' => "200",
            'group_id' => 3,
        ]);
        $this->insert('{{%property}}', [
            'id' => 9,
            'name' => "oldtown",
            'src' => "/web/images/cells/13.jpg",
            'cost' => "320",
            'rent' => "28",
            'rent_home1' => "150",
            'rent_home2' => "450",
            'rent_home3' => "1000",
            'rent_home4' => "1200",
            'rent_inn' => "1400",
            'homes_inn_cost' => "200",
            'group_id' => 3,
        ]);
        $this->insert('{{%property}}', [
            'id' => 10,
            'name' => "bravos",
            'src' => "/web/images/cells/16.jpg",
            'cost' => "200",
            'rent' => "16",
            'rent_home1' => "80",
            'rent_home2' => "220",
            'rent_home3' => "600",
            'rent_home4' => "800",
            'rent_inn' => "1000",
            'homes_inn_cost' => "100",
            'group_id' => 8,
        ]);
        $this->insert('{{%property}}', [
            'id' => 11,
            'name' => "qarth",
            'src' => "/web/images/cells/18.jpg",
            'cost' => "180",
            'rent' => "14",
            'rent_home1' => "70",
            'rent_home2' => "200",
            'rent_home3' => "550",
            'rent_home4' => "750",
            'rent_inn' => "950",
            'homes_inn_cost' => "100",
            'group_id' => 8,
        ]);
        $this->insert('{{%property}}', [
            'id' => 12,
            'name' => "dragonstone",
            'src' => "/web/images/cells/19.jpg",
            'cost' => "350",
            'rent' => "35",
            'rent_home1' => "175",
            'rent_home2' => "500",
            'rent_home3' => "1100",
            'rent_home4' => "1300",
            'rent_inn' => "1500",
            'homes_inn_cost' => "200",
            'group_id' => 4,
        ]);
        $this->insert('{{%property}}', [
            'id' => 13,
            'name' => "volantis",
            'src' => "/web/images/cells/22.jpg",
            'cost' => "180",
            'rent' => "14",
            'rent_home1' => "70",
            'rent_home2' => "200",
            'rent_home3' => "550",
            'rent_home4' => "750",
            'rent_inn' => "950",
            'homes_inn_cost' => "100",
            'group_id' => 8,
        ]);
        $this->insert('{{%property}}', [
            'id' => 14,
            'name' => "king's landing",
            'src' => "/web/images/cells/23.jpg",
            'cost' => "400",
            'rent' => "50",
            'rent_home1' => "200",
            'rent_home2' => "600",
            'rent_home3' => "1400",
            'rent_home4' => "1700",
            'rent_inn' => "2000",
            'homes_inn_cost' => "200",
            'group_id' => 4,
        ]);
        $this->insert('{{%property}}', [
            'id' => 15,
            'name' => "the eyrie",
            'src' => "/web/images/cells/26.jpg",
            'cost' => "160",
            'rent' => "12",
            'rent_home1' => "60",
            'rent_home2' => "180",
            'rent_home3' => "500",
            'rent_home4' => "700",
            'rent_inn' => "900",
            'homes_inn_cost' => "100",
            'group_id' => 7,
        ]);
        $this->insert('{{%property}}', [
            'id' => 16,
            'name' => "riverrun",
            'src' => "/web/images/cells/27.jpg",
            'cost' => "140",
            'rent' => "10",
            'rent_home1' => "50",
            'rent_home2' => "150",
            'rent_home3' => "450",
            'rent_home4' => "625",
            'rent_inn' => "750",
            'homes_inn_cost' => "100",
            'group_id' => 7,
        ]);
        $this->insert('{{%property}}', [
            'id' => 17,
            'name' => "the twins",
            'src' => "/web/images/cells/29.jpg",
            'cost' => "140",
            'rent' => "10",
            'rent_home1' => "50",
            'rent_home2' => "150",
            'rent_home3' => "450",
            'rent_home4' => "625",
            'rent_inn' => "750",
            'homes_inn_cost' => "100",
            'group_id' => 7,
        ]);
        $this->insert('{{%property}}', [
            'id' => 18,
            'name' => "winterfell",
            'src' => "/web/images/cells/30.jpg",
            'cost' => "120",
            'rent' => "8",
            'rent_home1' => "40",
            'rent_home2' => "100",
            'rent_home3' => "300",
            'rent_home4' => "450",
            'rent_inn' => "650",
            'homes_inn_cost' => "50",
            'group_id' => 6,
        ]);
        $this->insert('{{%property}}', [
            'id' => 19,
            'name' => "the wall",
            'src' => "/web/images/cells/31.jpg",
            'cost' => "100",
            'rent' => "6",
            'rent_home1' => "30",
            'rent_home2' => "90",
            'rent_home3' => "270",
            'rent_home4' => "400",
            'rent_inn' => "550",
            'homes_inn_cost' => "50",
            'group_id' => 6,
        ]);
        $this->insert('{{%property}}', [
            'id' => 20,
            'name' => "pyke",
            'src' => "/web/images/cells/33.jpg",
            'cost' => "100",
            'rent' => "6",
            'rent_home1' => "30",
            'rent_home2' => "90",
            'rent_home3' => "270",
            'rent_home4' => "400",
            'rent_inn' => "550",
            'homes_inn_cost' => "50",
            'group_id' => 6,
        ]);
        $this->insert('{{%property}}', [
            'id' => 21,
            'name' => "sunspear",
            'src' => "/web/images/cells/36.jpg",
            'cost' => "60",
            'rent' => "4",
            'rent_home1' => "20",
            'rent_home2' => "80",
            'rent_home3' => "180",
            'rent_home4' => "320",
            'rent_inn' => "450",
            'homes_inn_cost' => "50",
            'group_id' => 5,
        ]);
        $this->insert('{{%property}}', [
            'id' => 22,
            'name' => "tower of joy",
            'src' => "/web/images/cells/38.jpg",
            'cost' => "60",
            'rent' => "2",
            'rent_home1' => "10",
            'rent_home2' => "30",
            'rent_home3' => "90",
            'rent_home4' => "160",
            'rent_inn' => "250",
            'homes_inn_cost' => "50",
            'group_id' => 5,
        ]);


        //groups???????
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%property}}');
    }
}
