<?php

use yii\db\Migration;

/**
 * Class m240716_110234_create_prmisstions
 */
class m240716_110234_create_prmisstions extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $auth = Yii::$app->authManager;

        $newPermission = $auth->createPermission('اظهار خسارة الفاتورة بالمبيعات');
        $newPermission->description = 'اظهار خسارة الفاتورة بالمبيعات';
        $auth->add($newPermission);

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m240716_110234_create_prmisstions cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m240716_110234_create_prmisstions cannot be reverted.\n";

        return false;
    }
    */
}
