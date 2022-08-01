<?php

use yii\db\Migration;

/**
 * Class m191007_074826_alter_tbl_sales_invoice_add_column_disc2
 */
class m191007_074826_alter_tbl_sales_invoice_add_column_disc2 extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->execute("ALTER TABLE `tbl_sales_invoice_item` ADD `invoice_item_disc_amount2` DECIMAL(16,2) NULL AFTER `invoice_item_disc_percent`, ADD `invoice_item_disc_percent2` FLOAT NULL AFTER `invoice_item_disc_amount2`;");
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m191007_074826_alter_tbl_sales_invoice_add_column_disc2 cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m191007_074826_alter_tbl_sales_invoice_add_column_disc2 cannot be reverted.\n";

        return false;
    }
    */
}
