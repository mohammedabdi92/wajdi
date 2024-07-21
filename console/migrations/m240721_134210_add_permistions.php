<?php

use yii\db\Migration;

/**
 * Class m240721_134210_add_permistions
 */
class m240721_134210_add_permistions extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $auth = Yii::$app->authManager;

        // Create a new permission
        $newPermission = $auth->createPermission('حذف طلب فرط وجمع المواد');
        $newPermission->description = 'حذف طلب فرط وجمع المواد';
        $auth->add($newPermission);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m240721_134210_add_permistions cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m240721_134210_add_permistions cannot be reverted.\n";

        return false;
    }
    */
}
