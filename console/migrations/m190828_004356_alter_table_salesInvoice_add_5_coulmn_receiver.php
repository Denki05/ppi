<?php

use yii\db\Migration;

/**
 * Class m190828_004356_alter_table_salesInvoice_add_5_coulmn_receiver
 */
class m190828_004356_alter_table_salesInvoice_add_5_coulmn_receiver extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->execution("ALTER TABLE `tbl_sales_invoice` ADD `invoice_receiver` VARCHAR(255) NULL AFTER `invoice_comission_pay_date`, ADD `invoice_destination_address` TEXT NULL AFTER `invoice_receiver`, ADD `invoice_postal_code` VARCHAR(255) NULL AFTER `invoice_destination_address`, ADD `invoice_destination_city` VARCHAR(255) NULL AFTER `invoice_postal_code`, ADD `invoice_destination_province` VARCHAR(255) NULL AFTER `invoice_destination_city`;
");
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m190828_004356_alter_table_salesInvoice_add_5_coulmn_receiver cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m190828_004356_alter_table_salesInvoice_add_5_coulmn_receiver cannot be reverted.\n";

        return false;
    }
    */
}
