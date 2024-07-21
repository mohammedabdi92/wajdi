<?php

use yii\db\Migration;

/**
 * Class m240721_105219_update_separation_table
 */
class m240721_105219_update_separation_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%separations}}', 'conut_from', $this->double());
        $this->addColumn('{{%separations}}', 'conut_to', $this->double());
        $this->dropColumn('{{%separations}}', 'order_id');
        $this->dropColumn('{{%separations}}', 'count');


    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m240721_105219_update_separation_table cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m240721_105219_update_separation_table cannot be reverted.\n";

        return false;
    }
    */
}
