<?php

use yii\db\Migration;

/**
 * Class m220527_093806_create_table_inventory_order
 */
class m220527_093806_create_table_inventory_order extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%inventory_order}}', [
            'id' => $this->primaryKey(),
            'supplier_id' => $this->integer()->notNull(),
            'store_id' => $this->integer()->notNull(),
            'total_cost' => $this->float()->notNull(),
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
        echo "m220527_093806_create_table_inventory_order cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m220527_093806_create_table_inventory_order cannot be reverted.\n";

        return false;
    }
    */
}
