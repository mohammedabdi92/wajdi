<?php

use yii\db\Migration;

/**
 * Class m220730_120906_add_stor_id_to_order_product
 */
class m220730_120906_add_stor_id_to_order_product extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%order_product}}', 'store_id', $this->integer());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m220730_120906_add_stor_id_to_order_product cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m220730_120906_add_stor_id_to_order_product cannot be reverted.\n";

        return false;
    }
    */
}
