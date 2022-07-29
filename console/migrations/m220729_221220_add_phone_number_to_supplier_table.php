<?php

use yii\db\Migration;

/**
 * Class m220729_221220_add_phone_number_to_supplier_table
 */
class m220729_221220_add_phone_number_to_supplier_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%supplier}}', 'phone_number_2', $this->text());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m220729_221220_add_phone_number_to_supplier_table cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m220729_221220_add_phone_number_to_supplier_table cannot be reverted.\n";

        return false;
    }
    */
}
