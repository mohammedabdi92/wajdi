<?php

use yii\db\Migration;

/**
 * Class m240718_164820_alter_code_to_string
 */
class m240718_164820_alter_code_to_string extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->alterColumn('{{%product}}', 'item_code', $this->string(30));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m240718_164820_alter_code_to_string cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m240718_164820_alter_code_to_string cannot be reverted.\n";

        return false;
    }
    */
}
