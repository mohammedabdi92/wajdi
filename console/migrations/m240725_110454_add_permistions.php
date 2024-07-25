<?php

use yii\db\Migration;

/**
 * Class m240725_110454_add_permistions
 */
class m240725_110454_add_permistions extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $auth = Yii::$app->authManager;

        // Create a new permission
        $newPermission = $auth->createPermission('ظهور المنشورات على الرئيسية');
        $newPermission->description = 'ظهور المنشورات على الرئيسية';
        $auth->add($newPermission);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m240725_110454_add_permistions cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m240725_110454_add_permistions cannot be reverted.\n";

        return false;
    }
    */
}
