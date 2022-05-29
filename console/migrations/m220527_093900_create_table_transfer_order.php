<?php

use yii\db\Migration;

/**
 * Class m220527_093900_create_table_transfer_order
 */
class m220527_093900_create_table_transfer_order extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%transfer_order}}', [
            'id' => $this->primaryKey(),
            'from' => $this->integer()->notNull(),
            'to' => $this->integer()->notNull(),
            'status' => $this->integer()->notNull(),
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
        echo "m220527_093900_create_table_transfer_order cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m220527_093900_create_table_transfer_order cannot be reverted.\n";

        return false;
    }
    */
}
