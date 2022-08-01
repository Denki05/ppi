<?php

use yii\db\Migration;

/**
 * Class m190904_031610_alter_table_customer_add_column_customer_note
 */
class m190904_031610_alter_table_customer_add_column_customer_note extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->execution("ALTER TABLE `tbl_customer` ADD `customer_note` TEXT NULL AFTER `customer_phone2`;");
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m190904_031610_alter_table_customer_add_column_customer_note cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m190904_031610_alter_table_customer_add_column_customer_note cannot be reverted.\n";

        return false;
    }
    */
}
