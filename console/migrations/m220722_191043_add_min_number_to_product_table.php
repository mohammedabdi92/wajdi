<?php

use yii\db\Migration;

/**
 * Class m220722_191043_add_min_number_to_product_table
 */
class m220722_191043_add_min_number_to_product_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%product}}', 'min_number', $this->double());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m220722_191043_add_min_number_to_product_table cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m220722_191043_add_min_number_to_product_table cannot be reverted.\n";

        return false;
    }
    */
}
