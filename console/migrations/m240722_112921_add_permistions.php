<?php

use yii\db\Migration;

/**
 * Class m240722_112921_add_permistions
 */
class m240722_112921_add_permistions extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $auth = Yii::$app->authManager;

        // Create a new permission
        $newPermission = $auth->createPermission('اظهار المصروفات لشخص المدخل فقط');
        $newPermission->description = 'اظهار المصروفات لشخص المدخل فقط';
        $auth->add($newPermission);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m240722_112921_add_permistions cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m240722_112921_add_permistions cannot be reverted.\n";

        return false;
    }
    */
}
