<?php

use yii\db\Migration;

/**
 * Class m221015_144722_add_price_pf_vat_and_vat_price_discount_percent_to_product
 */
class m221015_144722_add_price_pf_vat_and_vat_price_discount_percent_to_product extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%product}}', 'price_pf_vat', $this->double());
        $this->addColumn('{{%product}}', 'vat', $this->double());
        $this->addColumn('{{%product}}', 'price_discount_percent', $this->double());

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m221015_144722_add_price_pf_vat_and_vat_price_discount_percent_to_product cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m221015_144722_add_price_pf_vat_and_vat_price_discount_percent_to_product cannot be reverted.\n";

        return false;
    }
    */
}
