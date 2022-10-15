<?php

use yii\db\Migration;

/**
 * Class m221015_144948_add_price_discount_amount_to_product
 */
class m221015_144948_add_price_discount_amount_to_product extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%product}}', 'price_discount_amount', $this->double());

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m221015_144948_add_price_discount_amount_to_product cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m221015_144948_add_price_discount_amount_to_product cannot be reverted.\n";

        return false;
    }
    */
}
