<?php

use yii\db\Migration;

/**
 * Class m200727_180957_add_row_to_user_table
 */
class m200727_160957_add_row_to_user_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {     
        $this->insert('{{%user}}',[
            'username'=>'admin',
            'passwordHash'=>Yii::$app->security->generatePasswordHash('adminpassword'),
            'authKey'=>Yii::$app->security->generateRandomString()
        ]);
        $this->insert('{{%user}}',[
            'username'=>'moderator01',
            'passwordHash'=>Yii::$app->security->generatePasswordHash('moderator01password'),
            'authKey'=>Yii::$app->security->generateRandomString()
        ]);
        $this->insert('{{%user}}',[
            'username'=>'moderator02',
            'passwordHash'=>Yii::$app->security->generatePasswordHash('moderator02password'),
            'authKey'=>Yii::$app->security->generateRandomString()
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%user}}');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo 'm200727_180957_add_row_to_user_table cannot be reverted.\n';

        return false;
    }
    */
}
