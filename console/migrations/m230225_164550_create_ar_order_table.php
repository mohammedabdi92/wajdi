<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%ar_order}}`.
 */
class m230225_164550_create_ar_order_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%ar_order}}', [
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
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%ar_order}}');
    }
}
