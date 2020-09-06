<?php
use yii\db\Migration;

/**
 * Class m200727_182014_rbac_add_roles_to_admin_and_moderator
 */
class m200727_182014_rbac_add_roles_to_admin_and_moderator extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $auth=Yii::$app->authManager;

        $adminRole=$auth->getRole('admin');
        $auth->assign($adminRole,app\models\User::findOne(['username'=>'admin'])->id);
        $moderatorRole=$auth->getRole('moderator');
        $auth->assign($moderatorRole,app\models\User::findOne(['username'=>'moderator01'])->id);
        $auth->assign($moderatorRole,app\models\User::findOne(['username'=>'moderator02'])->id);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo 'm200727_182014_rbac_add_roles_to_admin_and_moderator cannot be reverted.\n';

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo 'm200727_182014_rbac_add_roles_to_admin_and_moderator cannot be reverted.\n';

        return false;
    }
    */
}
