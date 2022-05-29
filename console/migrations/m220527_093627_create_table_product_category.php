<?php

use yii\db\Migration;

/**
 * Class m220527_093627_create_table_product_category
 */
class m220527_093627_create_table_product_category extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {

        $this->createTable('{{%product_category}}', [
            'id' => $this->primaryKey(),
            'name' => $this->text()->notNull(),
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
        echo "m220527_093627_create_table_product_category cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m220527_093627_create_table_product_category cannot be reverted.\n";

        return false;
    }
    */
}
