<?php

use yii\db\Migration;

/**
 * Class m230715_171946_add_dept_note_to_fk_order
 */
class m230715_171946_add_dept_note_to_fk_order extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%fk_order}}', 'dept_note', $this->text());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m230715_171946_add_dept_note_to_fk_order cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m230715_171946_add_dept_note_to_fk_order cannot be reverted.\n";

        return false;
    }
    */
}
