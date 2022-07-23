<?php

use yii\db\Migration;

/**
 * Class m220723_162940_add_inventory_order_id_and_inventory_order_date_product
 */
class m220723_162940_add_inventory_order_id_and_inventory_order_date_product extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%inventory_order}}', 'inventory_order_id', $this->string());
        $this->addColumn('{{%inventory_order}}', 'inventory_order_date', $this->integer());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m220723_162940_add_inventory_order_id_and_inventory_order_date_product cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m220723_162940_add_inventory_order_id_and_inventory_order_date_product cannot be reverted.\n";

        return false;
    }
    */
}
