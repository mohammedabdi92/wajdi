<?php

use yii\db\Migration;

/**
 * Class m240721_112952_update_rename_separation_table
 */
class m240721_112952_update_rename_separation_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->renameColumn('{{%separations}}', 'conut_to', 'count_to');
        $this->renameColumn('{{%separations}}', 'conut_from', 'count_from');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m240721_112952_update_rename_separation_table cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m240721_112952_update_rename_separation_table cannot be reverted.\n";

        return false;
    }
    */
}
