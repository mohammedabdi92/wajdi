<?php

use yii\db\Migration;

/**
 * Class m240728_131725_add_amount_paid_time_and_maintenance_cost_time_to_maintenance
 */
class m240728_131725_add_amount_paid_time_and_maintenance_cost_time_to_maintenance extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%maintenance}}', 'maintenance_cost_time', $this->integer());
        $this->addColumn('{{%maintenance}}', 'amount_paid_time', $this->integer());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m240728_131725_add_amount_paid_time_and_maintenance_cost_time_to_maintenance cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m240728_131725_add_amount_paid_time_and_maintenance_cost_time_to_maintenance cannot be reverted.\n";

        return false;
    }
    */
}
