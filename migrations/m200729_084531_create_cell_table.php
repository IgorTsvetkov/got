<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%cell}}`.
 */
class m200729_084531_create_cell_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%cell}}', [
            'id' => $this->primaryKey(),
            'position'=>$this->integer(),
            'event_id' => $this->string(),
            'castle_id' => $this->string(),
        ]);
        $this->insert('{{%cell}}',[
            'position'=>"0",
            'event_id' =>"1",
        ]);
        $this->insert('{{%cell}}',[
            'position'=>"1",
            'castle_id' =>"1",
        ]);
        $this->insert('{{%cell}}',[
            'position'=>"2",
            'event_id' =>"2",
        ]);
        $this->insert('{{%cell}}',[
            'position'=>"3",
            'castle_id' =>"2",
        ]);
        $this->insert('{{%cell}}',[
            'position'=>"4",
            'castle_id' =>"3",
        ]);
        $this->insert('{{%cell}}',[
            'position'=>"5",
            'event_id' =>"3",            
        ]);
        $this->insert('{{%cell}}',[
            'position'=>"6",
            'castle_id' =>"4",
        ]);
        $this->insert('{{%cell}}',[
            'position'=>"7",
            'castle_id' =>"5",
        ]);
        $this->insert('{{%cell}}',[
            'position'=>"8",
            'event_id' =>"4",
        ]);
        $this->insert('{{%cell}}',[
            'position'=>"9",
            'castle_id' =>"6",
        ]);
        $this->insert('{{%cell}}',[
            'position'=>"10",
            'castle_id' =>"7",
        ]);
        $this->insert('{{%cell}}',[
            'position'=>"11",
            'castle_id' =>"8",
        ]);
        $this->insert('{{%cell}}',[
            'position'=>"12",
            'event_id' =>"5",
        ]);
        $this->insert('{{%cell}}',[
            'position'=>"13",
            'castle_id' =>"9",
        ]);
        $this->insert('{{%cell}}',[
            'position'=>"14",
            'event_id' =>"6",
        ]);
        $this->insert('{{%cell}}',[
            'position'=>"15",            
            'event_id' =>"7",
        ]);
        $this->insert('{{%cell}}',[
            'position'=>"16",
            'castle_id' =>"10",
        ]);
        $this->insert('{{%cell}}',[
            'position'=>"17",
            'event_id'=>"16"
        ]);
        $this->insert('{{%cell}}',[
            'position'=>"18",
            'castle_id' =>"11",
        ]);
        $this->insert('{{%cell}}',[
            'position'=>"19",
            'castle_id' =>"12",
        ]);
        $this->insert('{{%cell}}',[
            'position'=>"20",            
            'event_id' =>"8",
        ]);
        $this->insert('{{%cell}}',[
            'position'=>"21",            
            'event_id' =>"9",
        ]);
        $this->insert('{{%cell}}',[
            'position'=>"22",
            'castle_id' =>"13",
        ]);
        $this->insert('{{%cell}}',[
            'position'=>"23",
            'castle_id' =>"14",
        ]);
        $this->insert('{{%cell}}',[
            'position'=>"24",            
            'event_id' =>"10",
        ]);
        $this->insert('{{%cell}}',[
            'position'=>"25",            
            'event_id' =>"11",
        ]);
        $this->insert('{{%cell}}',[
            'position'=>"26",
            'castle_id' =>"15",
        ]);
        $this->insert('{{%cell}}',[
            'position'=>"27",
            'castle_id' =>"16",
        ]);
        $this->insert('{{%cell}}',[
            'position'=>"28",            
            'event_id' =>"12",
        ]);
        $this->insert('{{%cell}}',[
            'position'=>"29",
            'castle_id' =>"17",
        ]);
        $this->insert('{{%cell}}',[
            'position'=>"30",
            'castle_id' =>"18",
        ]);
        $this->insert('{{%cell}}',[
            'position'=>"31",
            'castle_id' =>"19",
        ]);
        $this->insert('{{%cell}}',[
            'position'=>"32",            
            'event_id' =>"2",
        ]);
        $this->insert('{{%cell}}',[            
            'position'=>"33",
            'castle_id' =>"20",
        ]);
        $this->insert('{{%cell}}',[
            'position'=>"34",            
            'event_id' =>"13",
        ]);
        $this->insert('{{%cell}}',[
            'position'=>"35",            
            'event_id' =>"14",
        ]);
        $this->insert('{{%cell}}',[
            'position'=>"36",
            'castle_id' =>"21",
        ]);
        $this->insert('{{%cell}}',[
            'position'=>"37",            
            'event_id' =>"5",
        ]);
        $this->insert('{{%cell}}',[
            'position'=>"38",
            'castle_id' =>"22",
        ]);
        $this->insert('{{%cell}}',[
            'position'=>"39",            
            'event_id' =>"15",
        ]);
       
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%cell}}');
    }
}
