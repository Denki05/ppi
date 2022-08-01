<?php

use yii\db\Migration;

/**
 * Class m190918_032911_insert_auth_item_for_product
 */
class m190918_032911_insert_auth_item_for_product extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->execute("INSERT INTO `auth_item` (`name`, `type`, `description`, `rule_name`, `data`, `created_at`, `updated_at`) VALUES ('product.default.create', '2', 'Tambah Satuan Barang', NULL, NULL, NULL, NULL), ('product.default.delete', '2', 'Hapus Barang', NULL, NULL, NULL, NULL), ('product.default.index', '2', 'Lihat Daftar Barang', NULL, NULL, NULL, NULL), ('product.default.view', '2', 'Lihat Detil Barang', NULL, NULL, NULL, NULL), ('product.default.update', '2', 'Edit Barang', NULL, NULL, NULL, NULL);");
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m190918_032911_insert_auth_item_for_product cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m190918_032911_insert_auth_item_for_product cannot be reverted.\n";

        return false;
    }
    */
}
