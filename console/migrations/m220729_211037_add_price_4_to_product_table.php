<?php

use yii\db\Migration;

/**
 * Class m220729_211037_add_price_4_to_product_table
 */
class m220729_211037_add_price_4_to_product_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%product}}', 'price_4', $this->double());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m220729_211037_add_price_4_to_product_table cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m220729_211037_add_price_4_to_product_table cannot be reverted.\n";

        return false;
    }
    */
}
