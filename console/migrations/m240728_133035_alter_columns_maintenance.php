<?php

use yii\db\Migration;

/**
 * Class m240728_133035_alter_columns_maintenance
 */
class m240728_133035_alter_columns_maintenance extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->alterColumn('{{%maintenance}}', 'amount_paid', $this->double());
        $this->alterColumn('{{%maintenance}}', 'maintenance_cost', $this->double());
        $this->alterColumn('{{%maintenance}}', 'cost_difference', $this->double());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m240728_133035_alter_columns_maintenance cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m240728_133035_alter_columns_maintenance cannot be reverted.\n";

        return false;
    }
    */
}
