<?php

use yii\db\Migration;

/**
 * Class m240714_082537_add_indexes_to_damaged_table
 */
class m240714_082537_add_indexes_to_damaged_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createIndex(
            'idx-damaged-order_id',
            'damaged',
            'order_id'
        );

        $this->createIndex(
            'idx-damaged-product_id',
            'damaged',
            'product_id'
        );

        $this->createIndex(
            'idx-damaged-status',
            'damaged',
            'status'
        );

        $this->createIndex(
            'idx-damaged-status_note_id',
            'damaged',
            'status_note_id'
        );

        $this->createIndex(
            'idx-damaged-inventory_order_id',
            'damaged',
            'inventory_order_id'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropIndex(
            'idx-damaged-order_id',
            'damaged'
        );

        $this->dropIndex(
            'idx-damaged-product_id',
            'damaged'
        );

        $this->dropIndex(
            'idx-damaged-status',
            'damaged'
        );

        $this->dropIndex(
            'idx-damaged-status_note_id',
            'damaged'
        );

        $this->dropIndex(
            'idx-damaged-inventory_order_id',
            'damaged'
        );
    }

   

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m240714_082537_add_indexes_to_damaged_table cannot be reverted.\n";

        return false;
    }
    */
}
