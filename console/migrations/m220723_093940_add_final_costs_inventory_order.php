<?php

use yii\db\Migration;

/**
 * Class m220723_093940_add_final_costs_inventory_order
 */
class m220723_093940_add_final_costs_inventory_order extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%inventory_order_product}}', 'product_total_cost_final', $this->double());
        $this->addColumn('{{%inventory_order_product}}', 'product_cost_final', $this->double());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m220723_093940_add_final_costs_inventory_order cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m220723_093940_add_final_costs_inventory_order cannot be reverted.\n";

        return false;
    }
    */
}
