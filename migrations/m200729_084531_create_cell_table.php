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
            'property_id' => $this->string(),
            'utility_id'=>$this->string(),
            'event_id' => $this->string(),
            'tax_id'=>$this->string(),
            #kings road and trident
        ]);
        $this->insert('{{%cell}}',[
            'position'=>"0",
            'event_id' =>"1",
        ]);
        $this->insert('{{%cell}}',[
            'position'=>"1",
            'property_id' =>"1",
        ]);
        $this->insert('{{%cell}}',[
            'position'=>"2",
            'event_id' =>"2",
        ]);
        $this->insert('{{%cell}}',[
            'position'=>"3",
            'property_id' =>"2",
        ]);
        $this->insert('{{%cell}}',[
            'position'=>"4",
            'property_id' =>"3",
        ]);
        $this->insert('{{%cell}}',[
            'position'=>"5",
            'tax_id'=>"1"            
        ]);
        $this->insert('{{%cell}}',[
            'position'=>"6",
            'property_id' =>"4",
        ]);
        $this->insert('{{%cell}}',[
            'position'=>"7",
            'property_id' =>"5",
        ]);
        $this->insert('{{%cell}}',[
            'position'=>"8",
            'utility_id' =>"1",
        ]);
        $this->insert('{{%cell}}',[
            'position'=>"9",
            'property_id' =>"6",
        ]);
        $this->insert('{{%cell}}',[
            'position'=>"10",
            'property_id' =>"7",
        ]);
        $this->insert('{{%cell}}',[
            'position'=>"11",
            'property_id' =>"8",
        ]);
        $this->insert('{{%cell}}',[
            'position'=>"12",
            'event_id' =>"3",
        ]);
        $this->insert('{{%cell}}',[
            'position'=>"13",
            'property_id' =>"9",
        ]);
        $this->insert('{{%cell}}',[
            'position'=>"25",
            'tax_id'=>"2"
        ]);
        $this->insert('{{%cell}}',[
            'position'=>"15",            
            'event_id' =>"4",
        ]);
        $this->insert('{{%cell}}',[
            'position'=>"16",
            'property_id' =>"10",
        ]);
        $this->insert('{{%cell}}',[
            'position'=>"17",
            'event_id'=>"10"
        ]);
        $this->insert('{{%cell}}',[
            'position'=>"18",
            'property_id' =>"11",
        ]);
        $this->insert('{{%cell}}',[
            'position'=>"19",
            'property_id' =>"12",
        ]);
        $this->insert('{{%cell}}',[
            'position'=>"20",            
            'event_id' =>"5",
        ]);
        $this->insert('{{%cell}}',[
            'position'=>"21",            
            'event_id' =>"6",
        ]);
        $this->insert('{{%cell}}',[
            'position'=>"22",
            'property_id' =>"13",
        ]);
        $this->insert('{{%cell}}',[
            'position'=>"23",
            'property_id' =>"14",
        ]);
        $this->insert('{{%cell}}',[
            'position'=>"24",            
            'event_id' =>"7",
        ]);
        $this->insert('{{%cell}}',[
            'position'=>"14",            
            'tax_id' =>"3",
        ]);
        $this->insert('{{%cell}}',[
            'position'=>"26",
            'property_id' =>"15",
        ]);
        $this->insert('{{%cell}}',[
            'position'=>"27",
            'property_id' =>"16",
        ]);
        $this->insert('{{%cell}}',[
            'position'=>"28",            
            'utility_id' =>"2",
        ]);
        $this->insert('{{%cell}}',[
            'position'=>"29",
            'property_id' =>"17",
        ]);
        $this->insert('{{%cell}}',[
            'position'=>"30",
            'property_id' =>"18",
        ]);
        $this->insert('{{%cell}}',[
            'position'=>"31",
            'property_id' =>"19",
        ]);
        $this->insert('{{%cell}}',[
            'position'=>"32",            
            'event_id' =>"2",
        ]);
        $this->insert('{{%cell}}',[            
            'position'=>"33",
            'property_id' =>"20",
        ]);
        $this->insert('{{%cell}}',[
            'position'=>"34",            
            'tax_id' =>"4",
        ]);
        $this->insert('{{%cell}}',[
            'position'=>"35",            
            'event_id' =>"8",
        ]);
        $this->insert('{{%cell}}',[
            'position'=>"36",
            'property_id' =>"21",
        ]);
        $this->insert('{{%cell}}',[
            'position'=>"37",            
            'event_id' =>"3",
        ]);
        $this->insert('{{%cell}}',[
            'position'=>"38",
            'property_id' =>"22",
        ]);
        $this->insert('{{%cell}}',[
            'position'=>"39",            
            'event_id' =>"9",
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
