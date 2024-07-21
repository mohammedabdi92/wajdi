<?php

use yii\db\Migration;

/**
 * Class m240720_164821_add_store_id_to_separation_table
 */
class m240720_164821_add_store_id_to_separation_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%separations}}', 'store_id', $this->integer());
    }
    

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m240720_164821_add_store_id_to_separation_table cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m240720_164821_add_store_id_to_separation_table cannot be reverted.\n";

        return false;
    }
    */
}
