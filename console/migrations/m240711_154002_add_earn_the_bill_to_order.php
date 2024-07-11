<?php

use yii\db\Migration;

/**
 * Class m240711_154002_add_earn_the_bill_to_order
 */
class m240711_154002_add_earn_the_bill_to_order extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%order}}', 'earn_the_bill', $this->double());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m240711_154002_add_earn_the_bill_to_order cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m240711_154002_add_earn_the_bill_to_order cannot be reverted.\n";

        return false;
    }
    */
}
