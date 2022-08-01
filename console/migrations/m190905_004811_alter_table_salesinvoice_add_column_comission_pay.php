<?php

use yii\db\Migration;

/**
 * Class m190905_004811_alter_table_salesinvoice_add_column_comission_pay
 */
class m190905_004811_alter_table_salesinvoice_add_column_comission_pay extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->execute("ALTER TABLE `tbl_sales_invoice` ADD `invoice_comission_pay_percent` FLOAT NULL AFTER `invoice_destination_province`, ADD `invoice_comission_pay_amount` DECIMAL(16,2) NULL AFTER `invoice_comission_pay_percent`;");
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m190905_004811_alter_table_salesinvoice_add_column_comission_pay cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m190905_004811_alter_table_salesinvoice_add_column_comission_pay cannot be reverted.\n";

        return false;
    }
    */
}
