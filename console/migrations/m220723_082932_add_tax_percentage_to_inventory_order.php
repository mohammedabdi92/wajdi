<?php

use yii\db\Migration;

/**
 * Class m220723_082932_add_tax_percentage_to_inventory_order
 */
class m220723_082932_add_tax_percentage_to_inventory_order extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%inventory_order}}', 'tax_percentage', $this->integer());
        $this->addColumn('{{%inventory_order_product}}', 'tax_percentage', $this->integer());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m220723_082932_add_tax_percentage_to_inventory_order cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m220723_082932_add_tax_percentage_to_inventory_order cannot be reverted.\n";

        return false;
    }
    */
}
