<?php

use yii\db\Migration;

/**
 * Class m220910_114517_add_user_id_and_pull_date_order_table
 */
class m220910_114517_add_user_id_and_pull_date_order_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%financial_withdrawals}}', 'user_id', $this->integer());
        $this->addColumn('{{%financial_withdrawals}}', 'pull_date', $this->dateTime());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m220910_114517_add_user_id_and_pull_date_order_table cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m220910_114517_add_user_id_and_pull_date_order_table cannot be reverted.\n";

        return false;
    }
    */
}
