<?php

use yii\db\Migration;

/**
 * Class m191029_003040_alter_tbl_sales_invoice_add_column_invoice_discon_amount
 */
class m191029_003040_alter_tbl_sales_invoice_add_column_invoice_discon_amount extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->execute("ALTER TABLE `tbl_sales_invoice` ADD `invoice_disc_amount2` DECIMAL(16,2) NULL AFTER `invoice_disc_percent`;");
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m191029_003040_alter_tbl_sales_invoice_add_column_invoice_discon_amount cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m191029_003040_alter_tbl_sales_invoice_add_column_invoice_discon_amount cannot be reverted.\n";

        return false;
    }
    */
}
