<?php

use yii\db\Migration;

/**
 * Class m240716_112715_create_prmisstions
 */
class m240716_112715_create_prmisstions extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $auth = Yii::$app->authManager;

        $newPermission = $auth->createPermission('منع حفظ الفاتورة الخسرانة');
        $newPermission->description = 'منع حفظ الفاتورة الخسرانة';
        $auth->add($newPermission);

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m240716_112715_create_prmisstions cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m240716_112715_create_prmisstions cannot be reverted.\n";

        return false;
    }
    */
}
