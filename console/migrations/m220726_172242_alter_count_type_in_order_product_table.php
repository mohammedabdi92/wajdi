<?php

use yii\db\Migration;

/**
 * Class m220726_172242_alter_count_type_in_order_product_table
 */
class m220726_172242_alter_count_type_in_order_product_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->alterColumn('{{%order_product}}', 'count_type', $this->integer());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m220726_172242_alter_count_type_in_order_product_table cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m220726_172242_alter_count_type_in_order_product_table cannot be reverted.\n";

        return false;
    }
    */
}
