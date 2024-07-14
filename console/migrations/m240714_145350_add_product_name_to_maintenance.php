<?php

use yii\db\Migration;

/**
 * Class m240714_145350_add_product_name_to_maintenance
 */
class m240714_145350_add_product_name_to_maintenance extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%maintenance}}', 'product_name', $this->string(50));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m240714_145350_add_product_name_to_maintenance cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m240714_145350_add_product_name_to_maintenance cannot be reverted.\n";

        return false;
    }
    */
}
