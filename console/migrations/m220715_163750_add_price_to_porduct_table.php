<?php

use yii\db\Migration;

/**
 * Class m220715_163750_add_price_to_porduct_table
 */
class m220715_163750_add_price_to_porduct_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%product}}', 'price_1', $this->double());
        $this->addColumn('{{%product}}', 'price_2', $this->double());
        $this->addColumn('{{%product}}', 'price_3', $this->double());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m220715_163750_add_price_to_porduct_table cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m220715_163750_add_price_to_porduct_table cannot be reverted.\n";

        return false;
    }
    */
}
