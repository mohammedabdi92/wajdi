<?php

use yii\db\Migration;

/**
 * Class m230630_182056_create_table_returns_group
 */
class m230630_182056_create_table_returns_group extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%returns_group}}', [
            'id' => $this->primaryKey(),
            'order_id'=>$this->integer(),
            'note'=>$this->text(),
            'returner_name'=>$this->text(),
            'created_at' => $this->integer(),
            'created_by' => $this->integer(),
            'updated_at' => $this->integer(),
            'updated_by' => $this->integer(),
        ]);
        $this->addColumn('{{%returns}}', 'returns_group_id', $this->integer());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m230630_182056_create_table_returns_group cannot be reverted.\n";

        return false;
    }

}
