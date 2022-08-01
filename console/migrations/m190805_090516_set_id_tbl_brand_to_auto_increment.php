<?php

use yii\db\Migration;

/**
 * Class m190805_090516_set_id_tbl_brand_to_auto_increment
 */
class m190805_090516_set_id_tbl_brand_to_auto_increment extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->execute("ALTER TABLE `tbl_brand` CHANGE `id` `id` INT(11) NOT NULL AUTO_INCREMENT;");
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m190805_090516_set_id_tbl_brand_to_auto_increment cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m190805_090516_set_id_tbl_brand_to_auto_increment cannot be reverted.\n";

        return false;
    }
    */
}
