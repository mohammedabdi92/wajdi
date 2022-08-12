<?php

use yii\db\Migration;

/**
 * Class m220812_145614_create_table_financial_withdrawals
 */
class m220812_145614_create_table_financial_withdrawals extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%financial_withdrawals}}', [
            'id' => $this->primaryKey(),
            'amount' => $this->double(),
            'status' => $this->integer(2),
            'note' => $this->text(),
            'created_at' => $this->integer()->notNull(),
            'created_by' => $this->integer(),
            'updated_at' => $this->integer()->notNull(),
            'updated_by' => $this->integer(),
        ]);
        $this->createTable('{{%outlays}}', [
            'id' => $this->primaryKey(),
            'amount' => $this->double(),
            'note' => $this->text(),
            'image_name' => $this->string(255),
            'created_at' => $this->integer()->notNull(),
            'created_by' => $this->integer(),
            'updated_at' => $this->integer()->notNull(),
            'updated_by' => $this->integer(),
        ]);
        $this->createTable('{{%returns}}', [
            'id' => $this->primaryKey(),
            'order_id' => $this->integer(),
            'product_id' => $this->double(),
            'count' => $this->double(),
            'amount' => $this->double(),
            'created_at' => $this->integer()->notNull(),
            'created_by' => $this->integer(),
            'updated_at' => $this->integer()->notNull(),
            'updated_by' => $this->integer(),
        ]);
        $this->createTable('{{%damaged}}', [
            'id' => $this->primaryKey(),
            'status' => $this->integer(2),
            'order_id' => $this->integer(),
            'product_id' => $this->double(),
            'count' => $this->double(),
            'amount' => $this->double(),
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
        echo "m220812_145614_create_table_financial_withdrawals cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m220812_145614_create_table_financial_withdrawals cannot be reverted.\n";

        return false;
    }
    */
}
