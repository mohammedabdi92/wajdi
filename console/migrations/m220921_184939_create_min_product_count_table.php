<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%min_product_count}}`.
 */
class m220921_184939_create_min_product_count_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%min_product_count}}', [
            'id' => $this->primaryKey(),
            'product_id' => $this->integer(),
            'store_id' => $this->integer(),
            'count' => $this->double(),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%min_product_count}}');
    }
}
