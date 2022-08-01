<?php

use yii\db\Migration;

/**
 * Class m190805_085748_drop_foreign_key_FK_category_to_brand
 */
class m190805_085748_drop_foreign_key_FK_category_to_brand extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->execute("ALTER TABLE tbl_category DROP FOREIGN KEY FK_tbl_category_tbl_brand;");
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m190805_085748_drop_foreign_key_FK_category_to_brand cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m190805_085748_drop_foreign_key_FK_category_to_brand cannot be reverted.\n";

        return false;
    }
    */
}
