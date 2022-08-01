<?php

use yii\db\Migration;

/**
 * Class m190913_061303_alter_table_sales_invoice_item_add_column_packaging_id
 */
class m190913_061303_alter_table_sales_invoice_item_add_column_packaging_id extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->execute("ALTER TABLE `tbl_sales_invoice_item` ADD `packaging_id` INT NOT NULL AFTER `product_id`;");
        $this->execute("ALTER TABLE `tbl_sales_invoice_item` ADD CONSTRAINT `FK_tbl_sales_invoice_item_tbl_packaging` FOREIGN KEY (`packaging_id`) REFERENCES `tbl_packaging`(`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;");
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m190913_061303_alter_table_sales_invoice_item_add_column_packaging_id cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m190913_061303_alter_table_sales_invoice_item_add_column_packaging_id cannot be reverted.\n";

        return false;
    }
    */
}
