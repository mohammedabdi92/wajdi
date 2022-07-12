<?php

use yii\db\Migration;

/**
 * Class m220712_114436_alter_last_product_cost_in_inventory_order_product_table
 */
class m220712_114436_alter_last_product_cost_in_inventory_order_product_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->alterColumn('{{%inventory}}', 'last_product_cost', $this->float());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m220712_114436_alter_last_product_cost_in_inventory_order_product_table cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m220712_114436_alter_last_product_cost_in_inventory_order_product_table cannot be reverted.\n";

        return false;
    }
    */
}
