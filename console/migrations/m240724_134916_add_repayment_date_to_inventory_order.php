<?php

use yii\db\Migration;

/**
 * Class m240724_134916_add_repayment_date_to_inventory_order
 */
class m240724_134916_add_repayment_date_to_inventory_order extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%inventory_order}}', 'repayment_date', $this->integer());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m240724_134916_add_repayment_date_to_inventory_order cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m240724_134916_add_repayment_date_to_inventory_order cannot be reverted.\n";

        return false;
    }
    */
}
