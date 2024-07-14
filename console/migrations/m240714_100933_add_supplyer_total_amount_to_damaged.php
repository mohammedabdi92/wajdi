<?php

use yii\db\Migration;

/**
 * Class m240714_100933_add_supplyer_total_amount_to_damaged
 */
class m240714_100933_add_supplyer_total_amount_to_damaged extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%damaged}}', 'supplyer_total_amount', $this->double());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m240714_100933_add_supplyer_total_amount_to_damaged cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m240714_100933_add_supplyer_total_amount_to_damaged cannot be reverted.\n";

        return false;
    }
    */
}
