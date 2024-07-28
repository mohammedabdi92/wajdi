<?php

use yii\db\Migration;

/**
 * Class m240728_134454_add_cost_value_time_to_damaged
 */
class m240728_134454_add_cost_value_time_to_damaged extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%damaged}}', 'cost_value_time', $this->integer());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m240728_134454_add_cost_value_time_to_damaged cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m240728_134454_add_cost_value_time_to_damaged cannot be reverted.\n";

        return false;
    }
    */
}
