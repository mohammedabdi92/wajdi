<?php

use yii\db\Migration;

/**
 * Class m221230_190727_add_name_2_to_supplier
 */
class m221230_190727_add_name_2_to_supplier extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%supplier}}', 'name_2', $this->text());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m221230_190727_add_name_2_to_supplier cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m221230_190727_add_name_2_to_supplier cannot be reverted.\n";

        return false;
    }
    */
}
