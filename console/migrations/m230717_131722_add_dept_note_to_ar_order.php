<?php

use yii\db\Migration;

/**
 * Class m230717_131722_add_dept_note_to_ar_order
 */
class m230717_131722_add_dept_note_to_ar_order extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%ar_order}}', 'dept_note', $this->text());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m230717_131722_add_dept_note_to_ar_order cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m230717_131722_add_dept_note_to_ar_order cannot be reverted.\n";

        return false;
    }
    */
}
