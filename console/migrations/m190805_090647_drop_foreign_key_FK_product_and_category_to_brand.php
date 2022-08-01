<?php

use yii\db\Migration;

/**
 * Class m190805_090647_drop_foreign_key_FK_product_and_category_to_brand
 */
class m190805_090647_drop_foreign_key_FK_product_and_category_to_brand extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->execute("ALTER TABLE `tbl_product` ADD CONSTRAINT `FK_tbl_product_tbl_brand` FOREIGN KEY (`brand_id`) REFERENCES `tbl_brand`(`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;");
        $this->execute("ALTER TABLE `tbl_product` ADD CONSTRAINT `FK_tbl_product_tbl_brand_original` FOREIGN KEY (`original_brand_id`) REFERENCES `tbl_brand`(`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;");
        $this->execute("ALTER TABLE `tbl_category` ADD CONSTRAINT `FK_tbl_category_tbl_brand` FOREIGN KEY (`brand_id`) REFERENCES `tbl_brand`(`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;");
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m190805_090647_drop_foreign_key_FK_product_and_category_to_brand cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m190805_090647_drop_foreign_key_FK_product_and_category_to_brand cannot be reverted.\n";

        return false;
    }
    */
}
