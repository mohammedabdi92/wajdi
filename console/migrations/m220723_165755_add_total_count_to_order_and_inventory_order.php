<?php

use yii\db\Migration;

/**
 * Class m220723_165755_add_total_count_to_order_and_inventory_order
 */
class m220723_165755_add_total_count_to_order_and_inventory_order extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%inventory_order}}', 'total_count', $this->double());
        $this->addColumn('{{%order}}', 'total_count', $this->double());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m220723_165755_add_total_count_to_order_and_inventory_order cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m220723_165755_add_total_count_to_order_and_inventory_order cannot be reverted.\n";

        return false;
    }
    */
}
