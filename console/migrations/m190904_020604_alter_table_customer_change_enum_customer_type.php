<?php

use yii\db\Migration;

/**
 * Class m190904_020604_alter_table_customer_change_enum_customer_type
 */
class m190904_020604_alter_table_customer_change_enum_customer_type extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->execute("ALTER TABLE `tbl_customer` CHANGE `customer_type` `customer_type` ENUM('agen','bigreseller','smallreseller','generalreseller','general','industry') CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT 'general';");
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m190904_020604_alter_table_customer_change_enum_customer_type cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m190904_020604_alter_table_customer_change_enum_customer_type cannot be reverted.\n";

        return false;
    }
    */
}
