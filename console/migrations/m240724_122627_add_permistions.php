<?php

use yii\db\Migration;

/**
 * Class m240724_122627_add_permistions
 */
class m240724_122627_add_permistions extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $auth = Yii::$app->authManager;

        // Create a new permission
        $newPermission = $auth->createPermission('تعديل تاريخ البحث في المشتريات');
        $newPermission->description = 'تعديل تاريخ البحث في المشتريات';
        $auth->add($newPermission);

        $newPermission = $auth->createPermission('تعديل تاريخ البحث في المبيعات');
        $newPermission->description = 'تعديل تاريخ البحث في المبيعات';
        $auth->add($newPermission);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m240724_122627_add_permistions cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m240724_122627_add_permistions cannot be reverted.\n";

        return false;
    }
    */
}
