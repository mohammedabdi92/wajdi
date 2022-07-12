<?php

use yii\db\Migration;

/**
 * Class m220712_112539_add_store_id_to_inventory_order_product_table
 */
class m220712_112539_add_store_id_to_inventory_order_product_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%inventory_order_product}}', 'store_id', $this->integer());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m220712_112539_add_store_id_to_inventory_order_product_table cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m220712_112539_add_store_id_to_inventory_order_product_table cannot be reverted.\n";

        return false;
    }
    */
}
