<?php

use yii\db\Migration;

/**
 * Class m240718_151258_add_code_on_product
 */
class m240718_151258_add_code_on_product extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%product}}', 'item_code', $this->integer());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m240718_151258_add_code_on_product cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m240718_151258_add_code_on_product cannot be reverted.\n";

        return false;
    }
    */
}
