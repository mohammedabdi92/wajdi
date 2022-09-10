<?php

use yii\db\Migration;

/**
 * Class m220910_123924_add_user_id_and_pull_date_outlay_table
 */
class m220910_123924_add_user_id_and_pull_date_outlay_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%outlays}}', 'user_id', $this->integer());
        $this->addColumn('{{%outlays}}', 'pull_date', $this->dateTime());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m220910_123924_add_user_id_and_pull_date_outlay_table cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m220910_123924_add_user_id_and_pull_date_outlay_table cannot be reverted.\n";

        return false;
    }
    */
}
