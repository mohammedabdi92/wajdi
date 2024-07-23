<?php

use yii\db\Migration;

/**
 * Class m240723_144107_add_store_id_to_transactions
 */
class m240723_144107_add_store_id_to_transactions extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%transactions}}', 'store_id', $this->integer());

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m240723_144107_add_store_id_to_transactions cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m240723_144107_add_store_id_to_transactions cannot be reverted.\n";

        return false;
    }
    */
}
