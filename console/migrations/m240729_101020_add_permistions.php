<?php

use yii\db\Migration;

/**
 * Class m240729_101020_add_permistions
 */
class m240729_101020_add_permistions extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $auth = Yii::$app->authManager;

        // Create a new permission
        $newPermission = $auth->createPermission('اظهار المجموع في صفحة البيع');
        $newPermission->description = 'اظهار المجموع في صفحة البيع';
        $auth->add($newPermission);

        $newPermission = $auth->createPermission('اظهار المجموع في المرجع');
        $newPermission->description = 'اظهار المجموع في المرجع';
        $auth->add($newPermission);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m240729_101020_add_permistions cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m240729_101020_add_permistions cannot be reverted.\n";

        return false;
    }
    */
}
