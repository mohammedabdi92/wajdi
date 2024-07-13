<?php

use yii\db\Migration;

/**
 * Class m240713_080227_add_earn_the_bill_to_ar_order_and_orignal_cost_to_ar_order_product
 */
class m240713_080227_add_earn_the_bill_to_ar_order_and_orignal_cost_to_ar_order_product extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%ar_order_product}}', 'orignal_cost', $this->double());
        $this->addColumn('{{%ar_order}}', 'earn_the_bill', $this->double());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m240713_080227_add_earn_the_bill_to_ar_order_and_orignal_cost_to_ar_order_product cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m240713_080227_add_earn_the_bill_to_ar_order_and_orignal_cost_to_ar_order_product cannot be reverted.\n";

        return false;
    }
    */
}
