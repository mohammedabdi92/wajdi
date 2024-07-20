<?php

use yii\db\Migration;

/**
 * Class m240720_144942_add_invoice_footer_store
 */
class m240720_144942_add_invoice_footer_store extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%store}}', 'inv_footer_left', $this->text());
        $this->addColumn('{{%store}}', 'inv_footer_right', $this->text());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m240720_144942_add_invoice_footer_store cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m240720_144942_add_invoice_footer_store cannot be reverted.\n";

        return false;
    }
    */
}
