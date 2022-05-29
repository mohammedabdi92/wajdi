<?php

use yii\db\Migration;

/**
 * Class m220527_094421_create_table_customer
 */
class m220527_094421_create_table_customer extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%customer}}', [
            'id' => $this->primaryKey(),
            'name' => $this->text()->notNull(),
            'phone_number' => $this->text(),
            'email' => $this->text(),
            'address' => $this->text(),
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
        echo "m220527_094421_create_table_customer cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m220527_094421_create_table_customer cannot be reverted.\n";

        return false;
    }
    */
}
