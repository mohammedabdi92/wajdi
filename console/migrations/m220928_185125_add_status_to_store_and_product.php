<?php

use yii\db\Migration;

/**
 * Class m220928_185125_add_status_to_store_and_product
 */
class m220928_185125_add_status_to_store_and_product extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%store}}', 'status', $this->integer()->defaultValue(1));
        $this->addColumn('{{%product}}', 'status', $this->integer()->defaultValue(1));

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m220928_185125_add_status_to_store_and_product cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m220928_185125_add_status_to_store_and_product cannot be reverted.\n";

        return false;
    }
    */
}
