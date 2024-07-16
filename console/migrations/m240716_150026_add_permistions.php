<?php

use yii\db\Migration;

/**
 * Class m240716_150026_add_permistions
 */
class m240716_150026_add_permistions extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $auth = Yii::$app->authManager;

        $newPermission = $auth->createPermission('اظهار اخر واقل سعر في عرض المواد');
        $newPermission->description = 'اظهار اخر واقل سعر في عرض المواد';
        $auth->add($newPermission);

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m240716_150026_add_permistions cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m240716_150026_add_permistions cannot be reverted.\n";

        return false;
    }
    */
}
