<?php

use yii\db\Migration;

/**
 * Class m190904_015533_alter_table_customer_change_column_nullable
 */
class m190904_015533_alter_table_customer_change_column_nullable extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->execute("ALTER TABLE `tbl_customer` CHANGE `customer_zone` `customer_zone` VARCHAR(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL, CHANGE `customer_province` `customer_province` VARCHAR(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL, CHANGE `customer_city` `customer_city` VARCHAR(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL, CHANGE `customer_type` `customer_type` ENUM('agen','reseller','retail','general') CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT 'general', CHANGE `customer_has_tempo` `customer_has_tempo` TINYINT(1) NULL DEFAULT '0', CHANGE `customer_identity_card_number` `customer_identity_card_number` VARCHAR(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL, CHANGE `customer_identity_card_image` `customer_identity_card_image` VARCHAR(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL, CHANGE `customer_npwp` `customer_npwp` VARCHAR(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL, CHANGE `customer_npwp_image` `customer_npwp_image` VARCHAR(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL, CHANGE `customer_bank_name` `customer_bank_name` VARCHAR(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL, CHANGE `customer_bank_acc_number` `customer_bank_acc_number` VARCHAR(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL, CHANGE `customer_bank_acc_name` `customer_bank_acc_name` VARCHAR(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL, CHANGE `customer_has_ppn` `customer_has_ppn` TINYINT(1) NULL DEFAULT '0', CHANGE `customer_status` `customer_status` ENUM('active','inactive') CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT 'active';");
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m190904_015533_alter_table_customer_change_column_nullable cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m190904_015533_alter_table_customer_change_column_nullable cannot be reverted.\n";

        return false;
    }
    */
}
