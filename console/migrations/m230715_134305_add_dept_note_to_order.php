<?php

use yii\db\Migration;

/**
 * Class m230715_134305_add_dept_note_to_order
 */
class m230715_134305_add_dept_note_to_order extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%order}}', 'dept_note', $this->text());

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m230715_134305_add_dept_note_to_order cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m230715_134305_add_dept_note_to_order cannot be reverted.\n";

        return false;
    }
    */
}
