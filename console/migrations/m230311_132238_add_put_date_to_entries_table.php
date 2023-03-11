<?php

use yii\db\Migration;

/**
 * Class m230311_132238_add_put_date_to_entries_table
 */
class m230311_132238_add_put_date_to_entries_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%entries}}', 'put_date', $this->dateTime());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m230311_132238_add_put_date_to_entries_table cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m230311_132238_add_put_date_to_entries_table cannot be reverted.\n";

        return false;
    }
    */
}
