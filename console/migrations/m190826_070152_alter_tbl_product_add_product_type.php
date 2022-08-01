<?php

use yii\db\Migration;

/**
 * Class m190826_070152_alter_tbl_product_add_product_type
 */
class m190826_070152_alter_tbl_product_add_product_type extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->execute("ALTER TABLE `tbl_product` ADD `product_type` ENUM('sample','new','bestseller','regular','slow','discontinue') NOT NULL AFTER `product_status`;");
        $this->execute("ALTER TABLE `tbl_product` CHANGE `product_type` `product_type` ENUM('sample','new','bestseller','regular','slow','discontinue') CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL DEFAULT 'sample';");
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m190826_070152_alter_tbl_product_add_product_type cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m190826_070152_alter_tbl_product_add_product_type cannot be reverted.\n";

        return false;
    }
    */
}
