<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%user}}`.
 */
class m200727_121336_create_user_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%user}}', [
            'id' => $this->primaryKey(),
            'username'=>$this->string(36),
            'passwordHash'=>$this->string(63),
            'authKey'=>$this->string(32),
            'accessToken'=>$this->string(),
        ]);
        $this->insert('{{%user}}',[
            'id'=>'1',
            'username'=>'admin',
            'passwordHash'=>Yii::$app->security->generatePasswordHash('adminpassword'),
            'authKey'=>Yii::$app->security->generateRandomString(),
        ]);
        $this->insert('{{%user}}',[
            'id'=>'2',
            'username'=>'moderator01',
            'passwordHash'=>Yii::$app->security->generatePasswordHash('moderator01password'),
            'authKey'=>Yii::$app->security->generateRandomString()
        ]);
        $this->insert('{{%user}}',[
            'id'=>'3',
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
}
