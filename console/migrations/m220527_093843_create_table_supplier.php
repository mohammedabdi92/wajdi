<?php

use yii\db\Migration;

/**
 * Class m220527_093843_create_table_supplier
 */
class m220527_093843_create_table_supplier extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%supplier}}', [
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
        echo "m220527_093843_create_table_supplier cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m220527_093843_create_table_supplier cannot be reverted.\n";

        return false;
    }
    */
}
