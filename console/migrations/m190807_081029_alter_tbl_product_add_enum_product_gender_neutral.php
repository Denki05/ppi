<?php

use yii\db\Migration;

/**
 * Class m190807_081029_alter_tbl_product_add_enum_product_gender_neutral
 */
class m190807_081029_alter_tbl_product_add_enum_product_gender_neutral extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->execute("ALTER TABLE `tbl_product` CHANGE `product_gender` `product_gender` ENUM('m','f','neutral') CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL;");
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m190807_081029_alter_tbl_product_add_enum_product_gender_neutral cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m190807_081029_alter_tbl_product_add_enum_product_gender_neutral cannot be reverted.\n";

        return false;
    }
    */
}
