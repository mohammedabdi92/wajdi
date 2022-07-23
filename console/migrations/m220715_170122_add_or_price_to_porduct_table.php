<?php

use yii\db\Migration;

/**
 * Class m220715_170122_add_or_price_to_porduct_table
 */
class m220715_170122_add_or_price_to_porduct_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%product}}', 'price', $this->double());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m220715_170122_add_or_price_to_porduct_table cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m220715_170122_add_or_price_to_porduct_table cannot be reverted.\n";

        return false;
    }
    */
}
