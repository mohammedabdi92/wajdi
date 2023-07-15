<?php

use yii\db\Migration;

/**
 * Class m230715_130157_create_table_transactions
 */
class m230715_130157_create_table_transactions extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%transactions}}', [
            'id' => $this->primaryKey(),
            'customer_id' => $this->integer()->notNull(),
            'order_id' => $this->integer(),
            'amount' => $this->float(),
            'type' => $this->integer(),
            'note' => $this->text(),
            'created_at' => $this->integer()->notNull(),
            'created_by' => $this->integer(),
            'updated_at' => $this->integer()->notNull(),
            'updated_by' => $this->integer(),
            'isDeleted' => $this->integer(),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m230715_130157_create_table_transactions cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m230715_130157_create_table_transactions cannot be reverted.\n";

        return false;
    }
    */
}
