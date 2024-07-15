<?php

use yii\db\Migration;

/**
 * Class m240715_161038_add_store_id_to_maintenance
 */
class m240715_161038_add_store_id_to_maintenance extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%maintenance}}', 'store_id', $this->integer());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m240715_161038_add_store_id_to_maintenance cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m240715_161038_add_store_id_to_maintenance cannot be reverted.\n";

        return false;
    }
    */
}
