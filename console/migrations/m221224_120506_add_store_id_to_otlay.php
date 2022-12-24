<?php

use yii\db\Migration;

/**
 * Class m221224_120506_add_store_id_to_otlay
 */
class m221224_120506_add_store_id_to_otlay extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%outlays}}', 'store_id', $this->integer());
        $this->addColumn('{{%financial_withdrawals}}', 'store_id', $this->integer());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m221224_120506_add_store_id_to_otlay cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m221224_120506_add_store_id_to_otlay cannot be reverted.\n";

        return false;
    }
    */
}
