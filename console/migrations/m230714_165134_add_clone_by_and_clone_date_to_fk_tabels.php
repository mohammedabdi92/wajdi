<?php

use yii\db\Migration;

/**
 * Class m230714_165134_add_clone_by_and_clone_date_to_fk_tabels
 */
class m230714_165134_add_clone_by_and_clone_date_to_fk_tabels extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%fk_order}}', 'clone_by', $this->integer());
        $this->addColumn('{{%fk_order}}', 'clone_at', $this->integer());
        $this->addColumn('{{%fk_order_product}}', 'clone_by', $this->integer());
        $this->addColumn('{{%fk_order_product}}', 'clone_at', $this->integer());
        $this->addColumn('{{%fk_inventory_order}}', 'clone_by', $this->integer());
        $this->addColumn('{{%fk_inventory_order}}', 'clone_at', $this->integer());
        $this->addColumn('{{%fk_inventory_order_product}}', 'clone_by', $this->integer());
        $this->addColumn('{{%fk_inventory_order_product}}', 'clone_at', $this->integer());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m230714_165134_add_clone_by_and_clone_date_to_fk_tabels cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m230714_165134_add_clone_by_and_clone_date_to_fk_tabels cannot be reverted.\n";

        return false;
    }
    */
}
