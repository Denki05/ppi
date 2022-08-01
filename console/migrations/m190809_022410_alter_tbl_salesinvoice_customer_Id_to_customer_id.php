<?php

use yii\db\Migration;

/**
 * Class m190809_022410_alter_tbl_salesinvoice_customer_Id_to_customer_id
 */
class m190809_022410_alter_tbl_salesinvoice_customer_Id_to_customer_id extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->execute("ALTER TABLE `tbl_sales_invoice` CHANGE `customer_Id` `customer_id` INT(11) NOT NULL;");
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m190809_022410_alter_tbl_salesinvoice_customer_Id_to_customer_id cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m190809_022410_alter_tbl_salesinvoice_customer_Id_to_customer_id cannot be reverted.\n";

        return false;
    }
    */
}
