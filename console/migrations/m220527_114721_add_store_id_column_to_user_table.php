<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%user}}`.
 */
class m220527_114721_add_store_id_column_to_user_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%user}}', 'store_id', $this->integer());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
    }
}
