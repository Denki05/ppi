<?php

use yii\db\Migration;

/**
 * Class m190807_045106_update_column_category_and_material_set_to_null_in_tbl_product
 */
class m190807_045106_update_column_category_and_material_set_to_null_in_tbl_product extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->("ALTER TABLE `tbl_product` CHANGE `category_id` `category_id` INT(11) NULL;");
        $this->("ALTER TABLE `tbl_product` CHANGE `product_material_code` `product_material_code` VARCHAR(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL;");
        $this->("ALTER TABLE `tbl_product` CHANGE `product_material_name` `product_material_name` VARCHAR(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL;");
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m190807_045106_update_column_category_and_material_set_to_null_in_tbl_product cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m190807_045106_update_column_category_and_material_set_to_null_in_tbl_product cannot be reverted.\n";

        return false;
    }
    */
}
