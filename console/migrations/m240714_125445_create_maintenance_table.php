<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%maintenance}}`.
 */
class m240714_125445_create_maintenance_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('maintenance', [
            'id' => $this->primaryKey(),
            'client_id' => $this->integer()->notNull(),
            'item_count' => $this->integer()->notNull(),
            'client_note' => $this->text(),
            'status' =>  $this->integer(),
            'amount_paid' => $this->decimal(10, 2),
            'service_center_id' => $this->integer()->notNull(),
            'maintenance_note' => $this->text(),
            'maintenance_cost' => $this->decimal(10, 2),
            'cost_difference' => $this->decimal(10, 2),
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
        $this->dropTable('{{%maintenance}}');
    }
}
