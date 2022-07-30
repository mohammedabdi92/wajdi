<?php

use yii\db\Migration;

/**
 * Class m220730_101338_add_debt_and_repayment_to_inventory_order
 */
class m220730_101338_add_debt_and_repayment_to_inventory_order extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%inventory_order}}', 'debt', $this->double());
        $this->addColumn('{{%inventory_order}}', 'repayment', $this->double());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m220730_101338_add_debt_and_repayment_to_inventory_order cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m220730_101338_add_debt_and_repayment_to_inventory_order cannot be reverted.\n";

        return false;
    }
    */
}
