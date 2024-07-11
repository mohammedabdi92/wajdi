<?php

use yii\db\Migration;

/**
 * Class m240711_154841_add_orignal_cost_to_order_product
 */
class m240711_154841_add_orignal_cost_to_order_product extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%order_product}}', 'orignal_cost', $this->double());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m240711_154841_add_orignal_cost_to_order_product cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m240711_154841_add_orignal_cost_to_order_product cannot be reverted.\n";

        return false;
    }
    */
}
