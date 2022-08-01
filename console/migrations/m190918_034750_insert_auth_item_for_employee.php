<?php

use yii\db\Migration;

/**
 * Class m190918_034750_insert_auth_item_for_employee
 */
class m190918_034750_insert_auth_item_for_employee extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->execute("INSERT INTO `auth_item` (`name`, `type`, `description`, `rule_name`, `data`, `created_at`, `updated_at`) VALUES ('employee.default.create', '2', 'Tambah Pegawai Baru', NULL, NULL, NULL, NULL), ('employee.default.delete', '2', 'Hapus Pegawai', NULL, NULL, NULL, NULL), ('employee.default.index', '2', 'Lihat Daftar Pegawai', NULL, NULL, NULL, NULL), ('employee.default.update', '2', 'Edit Pegawai', NULL, NULL, NULL, NULL);");
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m190918_034750_insert_auth_item_for_employee cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m190918_034750_insert_auth_item_for_employee cannot be reverted.\n";

        return false;
    }
    */
}
