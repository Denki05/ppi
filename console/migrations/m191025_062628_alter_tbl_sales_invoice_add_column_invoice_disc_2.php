<?php

use yii\db\Migration;

/**
 * Class m191025_062628_alter_tbl_sales_invoice_add_column_invoice_disc_2
 */
class m191025_062628_alter_tbl_sales_invoice_add_column_invoice_disc_2 extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->execute("ALTER TABLE `tbl_sales_invoice` ADD `invoice_disc_percent2` FLOAT NULL AFTER `invoice_disc_percent`;");
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m191025_062628_alter_tbl_sales_invoice_add_column_invoice_disc_2 cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m191025_062628_alter_tbl_sales_invoice_add_column_invoice_disc_2 cannot be reverted.\n";

        return false;
    }
    */
}
