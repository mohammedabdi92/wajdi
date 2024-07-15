<?php

use yii\db\Migration;

/**
 * Class m240715_115242_create_prmisstions
 */
class m240715_115242_create_prmisstions extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $auth = Yii::$app->authManager;

        $newPermission = $auth->createPermission('اظهار المجموع في المصروفات');
        $newPermission->description = 'اظهار المجموع في المصروفات';
        $auth->add($newPermission);

        $newPermission = $auth->createPermission('اظهار المجاميع في مواد الصيانة');
        $newPermission->description = 'اظهار المجاميع في مواد الصيانة';
        $auth->add($newPermission);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m240715_115242_create_prmisstions cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m240715_115242_create_prmisstions cannot be reverted.\n";

        return false;
    }
    */
}
