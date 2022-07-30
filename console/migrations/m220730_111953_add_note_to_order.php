<?php

use yii\db\Migration;

/**
 * Class m220730_111953_add_note_to_order
 */
class m220730_111953_add_note_to_order extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%order}}', 'note', $this->text());
        $this->addColumn('{{%inventory_order}}', 'note', $this->text());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m220730_111953_add_note_to_order cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m220730_111953_add_note_to_order cannot be reverted.\n";

        return false;
    }
    */
}
