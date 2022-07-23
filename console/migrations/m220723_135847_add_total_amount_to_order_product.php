<?php

use yii\db\Migration;

/**
 * Class m220723_135847_add_total_amount_to_order_product
 */
class m220723_135847_add_total_amount_to_order_product extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%order_product}}', 'total_product_amount', $this->double());
        $this->addColumn('{{%order_product}}', 'discount', $this->double());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m220723_135847_add_total_amount_to_order_product cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m220723_135847_add_total_amount_to_order_product cannot be reverted.\n";

        return false;
    }
    */
}
