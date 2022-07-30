<?php

use yii\db\Migration;

/**
 * Class m220730_160116_add_product_id_and_count_to_transfer_order
 */
class m220730_160116_add_product_id_and_count_to_transfer_order extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%transfer_order}}', 'product_id', $this->integer());
        $this->addColumn('{{%transfer_order}}', 'count', $this->double());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m220730_160116_add_product_id_and_count_to_transfer_order cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m220730_160116_add_product_id_and_count_to_transfer_order cannot be reverted.\n";

        return false;
    }
    */
}
