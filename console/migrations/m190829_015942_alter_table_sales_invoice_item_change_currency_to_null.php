<?php

use yii\db\Migration;

/**
 * Class m190829_015942_alter_table_sales_invoice_item_change_currency_to_null
 */
class m190829_015942_alter_table_sales_invoice_item_change_currency_to_null extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->execute("ALTER TABLE `tbl_sales_invoice_item` CHANGE `invoice_item_currency` `invoice_item_currency` ENUM('rupiah','dolar') CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL;");
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m190829_015942_alter_table_sales_invoice_item_change_currency_to_null cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m190829_015942_alter_table_sales_invoice_item_change_currency_to_null cannot be reverted.\n";

        return false;
    }
    */
}
