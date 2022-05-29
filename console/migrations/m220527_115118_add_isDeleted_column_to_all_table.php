<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%all}}`.
 */
class m220527_115118_add_isDeleted_column_to_all_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%user}}', 'isDeleted', $this->integer(1));
        $this->addColumn('{{%product}}', 'isDeleted', $this->integer(1));
        $this->addColumn('{{%product_category}}', 'isDeleted', $this->integer(1));
        $this->addColumn('{{%customer}}', 'isDeleted', $this->integer(1));
        $this->addColumn('{{%order}}', 'isDeleted', $this->integer(1));
        $this->addColumn('{{%order_product}}', 'isDeleted', $this->integer(1));
        $this->addColumn('{{%inventory}}', 'isDeleted', $this->integer(1));
        $this->addColumn('{{%inventory_order}}', 'isDeleted', $this->integer(1));
        $this->addColumn('{{%inventory_order_product}}', 'isDeleted', $this->integer(1));
        $this->addColumn('{{%supplier}}', 'isDeleted', $this->integer(1));
        $this->addColumn('{{%transfer_order}}', 'isDeleted', $this->integer(1));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
    }
}
