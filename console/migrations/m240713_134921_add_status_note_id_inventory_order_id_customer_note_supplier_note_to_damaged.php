<?php

use yii\db\Migration;

/**
 * Class m240713_134921_add_status_note_id_inventory_order_id_customer_note_supplier_note_to_damaged
 */
class m240713_134921_add_status_note_id_inventory_order_id_customer_note_supplier_note_to_damaged extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%damaged}}', 'status_note_id', $this->integer());
        $this->addColumn('{{%damaged}}', 'inventory_order_id', $this->integer());
        $this->addColumn('{{%damaged}}', 'customer_note', $this->text());
        $this->addColumn('{{%damaged}}', 'supplier_note', $this->text());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m240713_134921_add_status_note_id_inventory_order_id_customer_note_supplier_note_to_damaged cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m240713_134921_add_status_note_id_inventory_order_id_customer_note_supplier_note_to_damaged cannot be reverted.\n";

        return false;
    }
    */
}
