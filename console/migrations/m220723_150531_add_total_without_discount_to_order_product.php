<?php

use yii\db\Migration;

/**
 * Class m220723_150531_add_total_without_discount_to_order_product
 */
class m220723_150531_add_total_without_discount_to_order_product extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%order}}', 'total_amount_without_discount', $this->double());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m220723_150531_add_total_without_discount_to_order_product cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m220723_150531_add_total_without_discount_to_order_product cannot be reverted.\n";

        return false;
    }
    */
}
