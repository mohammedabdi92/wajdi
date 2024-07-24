<?php

use yii\db\Migration;

/**
 * Class m240724_172413_create_item_location_on_product
 */
class m240724_172413_create_item_location_on_product extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%product}}', 'item_location', $this->text());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m240724_172413_create_item_location_on_product cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m240724_172413_create_item_location_on_product cannot be reverted.\n";

        return false;
    }
    */
}
