<?php

use yii\db\Migration;

/**
 * Class m220527_093734_create_table_inventory
 */
class m220527_093734_create_table_inventory extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%inventory}}', [
            'id' => $this->primaryKey(),
            'product_id' => $this->integer()->notNull(),
            'store_id' => $this->integer()->notNull(),
            'last_product_cost' => $this->float()->notNull(),
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
        echo "m220527_093734_create_table_inventory cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m220527_093734_create_table_inventory cannot be reverted.\n";

        return false;
    }
    */
}
