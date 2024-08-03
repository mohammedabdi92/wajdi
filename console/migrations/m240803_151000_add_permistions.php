<?php

use yii\db\Migration;

/**
 * Class m240803_151000_add_permistions
 */
class m240803_151000_add_permistions extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $auth = Yii::$app->authManager;

        // Create a new permission
        $newPermission = $auth->createPermission('تعديل وحذف السداد من حركات الدين');
        $newPermission->description = 'تعديل وحذف السداد من حركات الدين';
        $auth->add($newPermission);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m240803_151000_add_permistions cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m240803_151000_add_permistions cannot be reverted.\n";

        return false;
    }
    */
}
