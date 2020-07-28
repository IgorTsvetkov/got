<?php

use yii\db\Migration;

/**
 * Class m200727_172614_init_rbac
 */
class m200727_172614_init_rbac extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $auth=Yii::$app->authManager;
        
        //moderator
        $moderator=$auth->createRole("moderator");
        $auth->add($moderator);

        //admin
        $admin=$auth->createRole("admin");
        $auth->add($admin);
        $auth->addChild($admin,$moderator);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $auth=Yii::$app->authManager;

        $auth->removeAll();
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m200727_172614_init_rbac cannot be reverted.\n";

        return false;
    }
    */
}
