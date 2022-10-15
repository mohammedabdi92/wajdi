<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%shortage}}`.
 */
class m221015_091411_create_shortage_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%shortage}}', [
            'id' => $this->primaryKey(),
            'note' => $this->text(),
            'store_id' => $this->integer(),
            'product_id' => $this->integer(),
            'count' => $this->integer(),
            'created_at' => $this->integer()->notNull(),
            'created_by' => $this->integer(),
            'updated_at' => $this->integer()->notNull(),
            'updated_by' => $this->integer(),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%shortage}}');
    }
}
