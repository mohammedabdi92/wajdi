<?php

use yii\db\Migration;

/**
 * Class m220715_182224_add_amount_to_order_product_table
 */
class m220715_182224_add_amount_to_order_product_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%order_product}}', 'price_number', $this->integer(2));
        $this->addColumn('{{%order_product}}', 'amount', $this->double());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m220715_182224_add_amount_to_order_product_table cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m220715_182224_add_amount_to_order_product_table cannot be reverted.\n";

        return false;
    }
    */
}
