<?php

use yii\db\Migration;

/**
 * Class m190826_075446_alter_tbl_customer_change_enum_customer_status
 */
class m190826_075446_alter_tbl_customer_change_enum_customer_status extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->execute("ALTER TABLE `tbl_customer` CHANGE `customer_status` `customer_status` ENUM('active', 'inactive') CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL DEFAULT 'active';");
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m190826_075446_alter_tbl_customer_change_enum_customer_status cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m190826_075446_alter_tbl_customer_change_enum_customer_status cannot be reverted.\n";

        return false;
    }
    */
}
