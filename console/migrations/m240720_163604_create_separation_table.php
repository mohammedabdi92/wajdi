<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%separation}}`.
 */
class m240720_163604_create_separation_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('separations', [
            'id' => $this->primaryKey(),
            'product_id_from'=>$this->integer(),
            'product_id_to'=>$this->integer(),
            'count'=>$this->double(),
            'order_id'=>$this->integer(),
            'created_at' => $this->integer()->notNull(), // Add this line
            'created_by' => $this->integer()->notNull(), // Add this line
            'updated_at' => $this->integer(), // Add this line
            'updated_by' => $this->integer(), // Add this line
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%separation}}');
    }
}
