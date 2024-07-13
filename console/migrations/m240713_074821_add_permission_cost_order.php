<?php

use yii\db\Migration;

/**
 * Class m240713_074821_add_permission_cost_order
 */
class m240713_074821_add_permission_cost_order extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $auth = Yii::$app->authManager;

        // Create a new permission
        $newPermission = $auth->createPermission('اظهار التكلفة والربح في فاتورة المبيعات والارشيف');
        $newPermission->description = 'Description of the new permission';
        $auth->add($newPermission);
    }

    public function safeDown()
    {
        $auth = Yii::$app->authManager;

        // Remove the permission
        $permission = $auth->getPermission('اظهار التكلفة والربح في فاتورة المبيعات والارشيف');
        if ($permission) {
            $auth->remove($permission);
        }
    }
    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m240713_074821_add_permission_cost_order cannot be reverted.\n";

        return false;
    }
    */
}
