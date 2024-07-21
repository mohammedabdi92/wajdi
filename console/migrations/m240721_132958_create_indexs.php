<?php

use yii\db\Migration;

/**
 * Class m240721_132958_create_indexs
 */
class m240721_132958_create_indexs extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {

        $this->createIndex(
            'idx-returns-amount',
            'returns',
            'amount'
        );
        $this->createIndex(
            'idx-returns-product_id',
            'returns',
            'product_id'
        );
        $this->createIndex(
            'idx-returns-order_id',
            'returns',
            'order_id'
        );
        $this->createIndex(
            'idx-returns-count',
            'returns',
            'count'
        );
        $this->createIndex(
            'idx-returns_group-order_id',
            'returns_group',
            'order_id'
        );

        $this->createIndex(
            'idx-transactions-customer_id',
            'transactions',
            'customer_id'
        );
        $this->createIndex(
            'idx-transactions-order_id',
            'transactions',
            'order_id'
        );
        $this->createIndex(
            'idx-transactions-amount',
            'transactions',
            'amount'
        );
        $this->createIndex(
            'idx-separations-product_id_from',
            'separations',
            'product_id_from'
        );
        $this->createIndex(
            'idx-separations-product_id_to',
            'separations',
            'product_id_to'
        );
        $this->createIndex(
            'idx-separations-store_id',
            'separations',
            'store_id'
        );
        $this->createIndex(
            'idx-separations-count_from',
            'separations',
            'count_from'
        );

        $this->createIndex(
            'idx-separations-count_to',
            'separations',
            'count_to'
        );


    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m240721_132958_create_indexs cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m240721_132958_create_indexs cannot be reverted.\n";

        return false;
    }
    */
}
