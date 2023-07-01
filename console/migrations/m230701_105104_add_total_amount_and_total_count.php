<?php

use yii\db\Migration;

/**
 * Class m230701_105104_add_total_amount_and_total_count
 */
class m230701_105104_add_total_amount_and_total_count extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%returns_group}}', 'total_amount', $this->double());
        $this->addColumn('{{%returns_group}}', 'total_count', $this->integer());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m230701_105104_add_total_amount_and_total_count cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m230701_105104_add_total_amount_and_total_count cannot be reverted.\n";

        return false;
    }
    */
}
