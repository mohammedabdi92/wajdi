<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%ar_order_product}}`.
 */
class m230225_164601_create_ar_order_product_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {

        $this->createTable('{{%ar_order_product}}', [
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
        $this->dropTable('{{%ar_order_product}}');
    }
}
