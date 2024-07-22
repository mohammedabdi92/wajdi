<?php

use yii\db\Migration;

/**
 * Class m240722_153842_add_prices_to_product
 */
class m240722_153842_add_prices_to_product extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%product}}', 'price_next', $this->double());
        $this->addColumn('{{%product}}', 'price_1_next', $this->double());
        $this->addColumn('{{%product}}', 'price_2_next', $this->double());
        $this->addColumn('{{%product}}', 'price_3_next', $this->double());
        $this->addColumn('{{%product}}', 'price_4_next', $this->double());
        $this->addColumn('{{%product}}', 'count_next', $this->double());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m240722_153842_add_prices_to_product cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m240722_153842_add_prices_to_product cannot be reverted.\n";

        return false;
    }
    */
}
