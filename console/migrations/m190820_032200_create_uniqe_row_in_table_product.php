<?php

use yii\db\Migration;

/**
 * Class m190820_032200_create_uniqe_row_in_table_product
 */
class m190820_032200_create_uniqe_row_in_table_product extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->execute("ALTER TABLE tbl_product ADD UNIQUE row_unique(product_code, product_name, brand_id, category_id);");
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m190820_032200_create_uniqe_row_in_table_product cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m190820_032200_create_uniqe_row_in_table_product cannot be reverted.\n";

        return false;
    }
    */
}
