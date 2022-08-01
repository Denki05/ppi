<?php

use yii\db\Migration;

/**
 * Class m191025_083719_alter_table_packaging_add_column_packing_and_value
 */
class m191025_083719_alter_table_packaging_add_column_packing_and_value extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->execute("ALTER TABLE `tbl_packaging` ADD `packaging_value` FLOAT NOT NULL AFTER `packaging_name`, ADD `packaging_packing` VARCHAR(255) NOT NULL AFTER `packaging_value`;");
        $this->execute("UPDATE `tbl_packaging` SET `packaging_name` = '0.5 Kg Botol Alu', `packaging_value` = '0.5', `packaging_packing` = 'Botol Alu' WHERE `tbl_packaging`.`id` = 1;");
        $this->execute("UPDATE `tbl_packaging` SET `packaging_name` = '1 Kg Botol Alu', `packaging_value` = '1', `packaging_packing` = 'Botol Alu' WHERE `tbl_packaging`.`id` = 2;");
        $this->execute("UPDATE `tbl_packaging` SET `packaging_name` = '5 Kg Jirigen', `packaging_value` = '5', `packaging_packing` = 'Jirigen' WHERE `tbl_packaging`.`id` = 3;");
        $this->execute("INSERT INTO `tbl_packaging` (`id`, `packaging_name`, `packaging_value`, `packaging_packing`, `is_deleted`) VALUES (NULL, '25 Kg Drum', '25', 'Drum', '0');");
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m191025_083719_alter_table_packaging_add_column_packing_and_value cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m191025_083719_alter_table_packaging_add_column_packing_and_value cannot be reverted.\n";

        return false;
    }
    */
}
