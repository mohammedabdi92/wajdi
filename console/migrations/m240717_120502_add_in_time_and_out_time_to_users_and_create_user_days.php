<?php

use yii\db\Migration;

/**
 * Class m240717_120502_add_in_time_and_out_time_to_users_and_create_user_days
 */
class m240717_120502_add_in_time_and_out_time_to_users_and_create_user_days extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%user}}', 'in_time', $this->time());
        $this->addColumn('{{%user}}', 'out_time', $this->time());
        $this->createTable('{{%work_days}}', [
            'id' => $this->primaryKey(),
            'user_id'=>$this->integer(),
            'day_of_week' => $this->integer()->notNull()->comment('0=Sunday, 1=Monday, ..., 6=Saturday'),
        ]);
        

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m240717_120502_add_in_time_and_out_time_to_users_and_create_user_days cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m240717_120502_add_in_time_and_out_time_to_users_and_create_user_days cannot be reverted.\n";

        return false;
    }
    */
}
