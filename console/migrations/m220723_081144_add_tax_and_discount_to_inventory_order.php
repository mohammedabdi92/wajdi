<?php

use yii\db\Migration;

/**
 * Class m220723_081144_add_tax_and_discount_to_inventory_order
 */
class m220723_081144_add_tax_and_discount_to_inventory_order extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%inventory_order}}', 'tax', $this->double());
        $this->addColumn('{{%inventory_order}}', 'discount_percentage', $this->double());
        $this->addColumn('{{%inventory_order}}', 'discount', $this->double());

        $this->addColumn('{{%inventory_order_product}}', 'discount_percentage', $this->double());

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m220723_081144_add_tax_and_discount_to_inventory_order cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m220723_081144_add_tax_and_discount_to_inventory_order cannot be reverted.\n";

        return false;
    }
    */
}
