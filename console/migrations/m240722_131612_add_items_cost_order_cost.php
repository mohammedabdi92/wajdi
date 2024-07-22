<?php

use yii\db\Migration;

/**
 * Class m240722_131612_add_items_cost_order_cost
 */
class m240722_131612_add_items_cost_order_cost extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%order}}', 'order_cost', $this->double());
        $this->addColumn('{{%order_product}}', 'items_cost', $this->double());

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m240722_131612_add_items_cost_order_cost cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m240722_131612_add_items_cost_order_cost cannot be reverted.\n";

        return false;
    }
    */
}
