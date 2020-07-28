<?php

use yii\db\Migration;

/**
 * Class m200727_181742_rbac_add_roles_to_admin_and_moderator
 */
class m200727_181742_rbac_add_roles_to_admin_and_moderator extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $auth=Yii::$app->authManager;

        $adminRole=$auth->getRole("admin");
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m200727_181742_rbac_add_roles_to_admin_and_moderator cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m200727_181742_rbac_add_roles_to_admin_and_moderator cannot be reverted.\n";

        return false;
    }
    */
}
