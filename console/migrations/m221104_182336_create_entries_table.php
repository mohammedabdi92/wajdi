<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%entries}}`.
 */
class m221104_182336_create_entries_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%entries}}', [
            'id' => $this->primaryKey(),
            'store_id'=>$this->integer(),
            'amount'=>$this->double(),
            'created_at' => $this->integer(),
            'created_by' => $this->integer(),
            'updated_at' => $this->integer(),
            'updated_by' => $this->integer(),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%entries}}');
    }
}
