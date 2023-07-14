<?php

use yii\db\Migration;

/**
 * Class m230714_135351_create_fk_order_and_fk_order_product
 */
class m230714_135351_create_fk_order_and_fk_order_product extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%fk_order}}', [
            'id' => $this->primaryKey(),
            'customer_id' => $this->integer()->notNull(),
            'store_id' => $this->integer()->notNull(),
            'total_amount' => $this->float()->notNull(),
            'created_at' => $this->integer()->notNull(),
            'created_by' => $this->integer(),
            'updated_at' => $this->integer()->notNull(),
            'updated_by' => $this->integer(),
            'isDeleted' => $this->integer(),
            'total_discount' => $this->double(),
            'total_amount_without_discount' => $this->double(),
            'total_price_discount_product' => $this->double(),
            'total_count' => $this->double(),
            'debt' => $this->double(),
            'repayment' => $this->double(),
            'note' => $this->text(),
            'paid' => $this->double(),
            'remaining' => $this->double(),
            'product_count' => $this->integer(),
        ]);
        $this->createTable('{{%fk_order_product}}', [
            'id' => $this->primaryKey(),
            'order_id' => $this->integer()->notNull(),
            'product_id' => $this->integer()->notNull(),
            'count' => $this->float()->notNull(),
            'count_type' => $this->integer(),
            'created_at' => $this->integer()->notNull(),
            'created_by' => $this->integer(),
            'updated_at' => $this->integer()->notNull(),
            'updated_by' => $this->integer(),
            'isDeleted' => $this->integer(),
            'price_number' => $this->integer(),
            'amount' => $this->double(),
            'total_product_amount' => $this->double(),
            'discount' => $this->double(),
            'store_id' => $this->integer(),
        ]);

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m230714_135351_create_fk_order_and_fk_order_product cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m230714_135351_create_fk_order_and_fk_order_product cannot be reverted.\n";

        return false;
    }
    */
}
