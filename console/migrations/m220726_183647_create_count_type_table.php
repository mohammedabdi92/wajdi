<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%count_type}}`.
 */
class m220726_183647_create_count_type_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%count_type}}', [
            'id' => $this->primaryKey(),
            'name'=>$this->string(),
            'status'=>$this->integer(2),
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
        $this->dropTable('{{%count_type}}');
    }
}
