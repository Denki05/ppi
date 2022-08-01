<?php

use yii\db\Migration;

/**
 * Class m191025_041105_alter_tbl_sles_invoice_add_column_shipping_cost
 */
class m191025_041105_alter_tbl_sles_invoice_add_column_shipping_cost extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->execute("ALTER TABLE `tbl_sales_invoice` ADD `invoice_shipping_cost` DECIMAL(16,2) NULL DEFAULT '0' AFTER `invoice_comission_pay_amount`;");
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m191025_041105_alter_tbl_sles_invoice_add_column_shipping_cost cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m191025_041105_alter_tbl_sles_invoice_add_column_shipping_cost cannot be reverted.\n";

        return false;
    }
    */
}
