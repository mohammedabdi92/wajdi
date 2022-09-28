<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%user_store}}`.
 */
class m220928_173720_create_user_store_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%user_store}}', [
            'id' => $this->primaryKey(),
            'user_id' => $this->integer(),
            'store_id' => $this->integer(),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%user_store}}');
    }
}
