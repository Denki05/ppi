<?php

use yii\db\Migration;

/**
 * Class m190918_034526_insert_auth_item_for_comissiontype
 */
class m190918_034526_insert_auth_item_for_comissiontype extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->execute("INSERT INTO `auth_item` (`name`, `type`, `description`, `rule_name`, `data`, `created_at`, `updated_at`) VALUES ('comissiontype.default.create', '2', 'Tambah Tipe Komisi Baru', NULL, NULL, NULL, NULL), ('comissiontype.default.delete', '2', 'Hapus Tipe Komisi', NULL, NULL, NULL, NULL), ('comissiontype.default.index', '2', 'Lihat Daftar Tipe Komisi', NULL, NULL, NULL, NULL), ('comissiontype.default.update', '2', 'Edit Tipe Komisi', NULL, NULL, NULL, NULL);");
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m190918_034526_insert_auth_item_for_comissiontype cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m190918_034526_insert_auth_item_for_comissiontype cannot be reverted.\n";

        return false;
    }
    */
}
