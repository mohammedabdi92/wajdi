<?php

use yii\db\Migration;

/**
 * Class m220921_190439_insert_to_store_table
 */
class m220921_190439_insert_to_store_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        foreach (\common\components\Constants::storeArray as $key=>$value) {
            $this->insert('store',array(
                'id'=>$key,
                'name'=> $value
            ));
        }


    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m220921_190439_insert_to_store_table cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m220921_190439_insert_to_store_table cannot be reverted.\n";

        return false;
    }
    */
}
