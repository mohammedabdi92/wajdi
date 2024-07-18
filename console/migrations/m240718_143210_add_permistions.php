<?php

use yii\db\Migration;

/**
 * Class m240718_143210_add_permistions
 */
class m240718_143210_add_permistions extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $auth = Yii::$app->authManager;

        // Create a new permission
        $newPermission = $auth->createPermission('نسخ فاتورة المبيعات الى الارشيف');
        $newPermission->description = 'نسخ فاتورة المبيعات الى الارشيف';
        $auth->add($newPermission);

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m240718_143210_add_permistions cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m240718_143210_add_permistions cannot be reverted.\n";

        return false;
    }
    */
}
