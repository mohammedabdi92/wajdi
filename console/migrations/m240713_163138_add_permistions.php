<?php

use yii\db\Migration;

/**
 * Class m240713_163138_add_permistions
 */
class m240713_163138_add_permistions extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {

        $auth = Yii::$app->authManager;

        // Create a new permission
        $newPermission = $auth->createPermission('تعديل وحذف بضاعة تالفة من المحل للمورد');
        $newPermission->description = 'تعديل وحذف بضاعة تالفة من المحل للمورد';
        $auth->add($newPermission);

        $newPermission = $auth->createPermission('تعديل وحذف بضاعة تالفة من العميل للمحل');
        $newPermission->description = 'تعديل وحذف بضاعة تالفة من المحل للمورد';
        $auth->add($newPermission);

        $newPermission = $auth->createPermission('انشاء فاتورة المشتريات');
        $newPermission->description = 'انشاء فاتورة المشتريات';
        $auth->add($newPermission);

        $permission = $auth->getPermission('تعديل وحذف بضاعة تالفة من العميل للمورد');
        if ($permission) {
            $auth->remove($permission);
        }
        $permission = $auth->getPermission('انشاء وتعديل فاتورة المشتريات');
        if ($permission) {
            $auth->remove($permission);
        }

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m240713_163138_add_permistions cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m240713_163138_add_permistions cannot be reverted.\n";

        return false;
    }
    */
}
