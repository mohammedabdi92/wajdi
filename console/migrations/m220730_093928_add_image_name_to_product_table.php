<?php

use yii\db\Migration;

/**
 * Class m220730_093928_add_image_name_to_product_table
 */
class m220730_093928_add_image_name_to_product_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%product}}', 'image_name', $this->text());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m220730_093928_add_image_name_to_product_table cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m220730_093928_add_image_name_to_product_table cannot be reverted.\n";

        return false;
    }
    */
}
