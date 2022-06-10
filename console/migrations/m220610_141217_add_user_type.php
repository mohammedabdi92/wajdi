<?php

use yii\db\Migration;

/**
 * Class m220610_141217_add_user_type
 */
class m220610_141217_add_user_type extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%user}}', 'type', $this->integer(1));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m220610_141217_add_user_type cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m220610_141217_add_user_type cannot be reverted.\n";

        return false;
    }
    */
}
