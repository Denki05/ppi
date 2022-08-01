<?php

use yii\db\Migration;

/**
 * Class m190805_090308_drop_foreign_key_FK_product_to_original_brand
 */
class m190805_090308_drop_foreign_key_FK_product_to_original_brand extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->execute("ALTER TABLE tbl_product DROP FOREIGN KEY FK_tbl_product_tbl_brand_original;");
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m190805_090308_drop_foreign_key_FK_product_to_original_brand cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m190805_090308_drop_foreign_key_FK_product_to_original_brand cannot be reverted.\n";

        return false;
    }
    */
}
