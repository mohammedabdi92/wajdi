<?php

use yii\db\Migration;

/**
 * Class m220910_110725_add_product_count_to_order_table
 */
class m220910_110725_add_product_count_to_order_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%order}}', 'product_count', $this->integer());

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m220910_110725_add_product_count_to_order_table cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m220910_110725_add_product_count_to_order_table cannot be reverted.\n";

        return false;
    }
    */
}
