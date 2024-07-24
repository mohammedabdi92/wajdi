<?php

use yii\db\Migration;

/**
 * Class m240724_142750_add_permistions
 */
class m240724_142750_add_permistions extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $auth = Yii::$app->authManager;

        // Create a new permission
        $newPermission = $auth->createPermission('اظهار المجاميع في فاتورة المشتريات');
        $newPermission->description = 'اظهار المجاميع في فاتورة المشتريات';
        $auth->add($newPermission);

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m240724_142750_add_permistions cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m240724_142750_add_permistions cannot be reverted.\n";

        return false;
    }
    */
}
