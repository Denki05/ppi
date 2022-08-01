<?php

use yii\db\Migration;

/**
 * Class m190904_013901_alter_table_customer_add_comun_store_potal_code_and_store_address
 */
class m190904_013901_alter_table_customer_add_comun_store_potal_code_and_store_address extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->execute("ALTER TABLE `tbl_customer` ADD `customer_store_postal_code` VARCHAR(255) NOT NULL AFTER `customer_store_name`, ADD `customer_store_address` VARCHAR(255) NOT NULL AFTER `customer_store_postal_code`;");
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m190904_013901_alter_table_customer_add_comun_store_potal_code_and_store_address cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m190904_013901_alter_table_customer_add_comun_store_potal_code_and_store_address cannot be reverted.\n";

        return false;
    }
    */
}
