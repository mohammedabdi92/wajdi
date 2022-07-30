<?php

use yii\db\Migration;

/**
 * Class m220730_164818_alter_status_to_transfer_order
 */
class m220730_164818_alter_status_to_transfer_order extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->alterColumn('{{%transfer_order}}', 'status', $this->integer());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m220730_164818_alter_status_to_transfer_order cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m220730_164818_alter_status_to_transfer_order cannot be reverted.\n";

        return false;
    }
    */
}
