<?php

use yii\db\Migration;

/**
 * Class m221224_155049_add_returner_name_to_returns_table
 */
class m221224_155049_add_returner_name_to_returns_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%returns}}', 'returner_name', $this->text());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m221224_155049_add_returner_name_to_returns_table cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m221224_155049_add_returner_name_to_returns_table cannot be reverted.\n";

        return false;
    }
    */
}
