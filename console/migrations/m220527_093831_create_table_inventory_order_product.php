<?php

use yii\db\Migration;

/**
 * Class m220527_093831_create_table_inventory_order_product
 */
class m220527_093831_create_table_inventory_order_product extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%inventory_order_product}}', [
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
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m220527_093831_create_table_inventory_order_product cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m220527_093831_create_table_inventory_order_product cannot be reverted.\n";

        return false;
    }
    */
}
