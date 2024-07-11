<?php

use yii\db\Migration;

/**
 * Class m240711_132346_add_old_single_amount_and_old_amount_to_returns_and_total_old_amount
 */
class m240711_132346_add_old_single_amount_and_old_amount_to_returns_and_total_old_amount extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%returns}}', 'old_single_amount', $this->double());
        $this->addColumn('{{%returns}}', 'old_amount', $this->double());
        $this->addColumn('{{%returns_group}}', 'total_old_amount', $this->double());

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m240711_132346_add_old_single_amount_and_old_amount_to_returns_and_total_old_amount cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m240711_132346_add_old_single_amount_and_old_amount_to_returns_and_total_old_amount cannot be reverted.\n";

        return false;
    }
    */
}
