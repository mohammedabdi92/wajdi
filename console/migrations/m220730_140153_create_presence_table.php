<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%presence}}`.
 */
class m220730_140153_create_presence_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%presence}}', [
            'id' => $this->primaryKey(),
            'user_id' => $this->integer(),
            'time' => $this->timestamp(),
            'type' => $this->integer(2),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%presence}}');
    }
}
