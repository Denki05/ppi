<?php

use yii\db\Migration;

/**
 * Class m190828_034332_alter_table_purchasepayment_change_purchase_payment_method_to_null
 */
class m190828_034332_alter_table_purchasepayment_change_purchase_payment_method_to_null extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->execute("ALTER TABLE `tbl_purchase_payment` CHANGE `purchase_payment_method` `purchase_payment_method` ENUM('cash','banktransfer') CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL;");
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m190828_034332_alter_table_purchasepayment_change_purchase_payment_method_to_null cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m190828_034332_alter_table_purchasepayment_change_purchase_payment_method_to_null cannot be reverted.\n";

        return false;
    }
    */
}
