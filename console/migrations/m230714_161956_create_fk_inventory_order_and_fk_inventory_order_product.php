<?php

use yii\db\Migration;

/**
 * Class m230714_161956_create_fk_inventory_order_and_fk_inventory_order_product
 */
class m230714_161956_create_fk_inventory_order_and_fk_inventory_order_product extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {

        $this->createTable('{{%fk_inventory_order}}', [
            'id' => $this->primaryKey(),
            'supplier_id' => $this->integer()->notNull(),
            'store_id' => $this->integer()->notNull(),
            'total_cost' => $this->float()->notNull(),
            'created_at' => $this->integer()->notNull(),
            'created_by' => $this->integer(),
            'updated_at' => $this->integer()->notNull(),
            'updated_by' => $this->integer(),
            'isDeleted' => $this->integer(),
            'tax' => $this->double(),
            'discount_percentage' => $this->double(),
            'discount' => $this->double(),
            'tax_percentage' => $this->integer(),
            'inventory_order_id' => $this->integer(15),
            'inventory_order_date' => $this->integer(),
            'total_count' => $this->double(),
            'debt' => $this->double(),
            'repayment' => $this->double(),
            'note' => $this->text(),

        ]);
        $this->createTable('{{%fk_inventory_order_product}}', [
            'id' => $this->primaryKey(),
            'inventory_order_id' => $this->integer()->notNull(),
            'product_id' => $this->integer()->notNull(),
            'product_total_cost' => $this->float()->notNull(),
            'product_cost' => $this->float()->notNull(),
            'count' => $this->float()->notNull(),
            'created_at' => $this->integer()->notNull(),
            'created_by' => $this->integer(),
            'updated_at' => $this->integer()->notNull(),
            'updated_by' => $this->integer(),
            'isDeleted' => $this->integer(),
            'store_id' => $this->integer(),
            'tax_percentage' => $this->integer(),
            'tax' => $this->double(),
            'discount_percentage' => $this->double(),
            'discount' => $this->double(),
            'product_total_cost_final' => $this->double(),
            'product_cost_final' => $this->double(),
        ]);

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m230714_161956_create_fk_inventory_order_and_fk_inventory_order_product cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m230714_161956_create_fk_inventory_order_and_fk_inventory_order_product cannot be reverted.\n";

        return false;
    }
    */
}
