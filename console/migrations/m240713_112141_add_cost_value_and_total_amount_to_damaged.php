<?php

use yii\db\Migration;

/**
 * Class m240713_112141_add_cost_value_and_total_amount_to_damaged
 */
class m240713_112141_add_cost_value_and_total_amount_to_damaged extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%damaged}}', 'cost_value', $this->double());
        $this->addColumn('{{%damaged}}', 'total_amount', $this->double());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m240713_112141_add_cost_value_and_total_amount_to_damaged cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m240713_112141_add_cost_value_and_total_amount_to_damaged cannot be reverted.\n";

        return false;
    }
    */
}
