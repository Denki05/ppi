<?php

use yii\db\Migration;

/**
 * Class m190918_032331_insert_auth_item_for_setting
 */
class m190918_032331_insert_auth_item_for_setting extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->execute("INSERT INTO `auth_item` (`name`, `type`, `description`, `rule_name`, `data`, `created_at`, `updated_at`) VALUES ('setting.backup.create', '2', 'Buat Backup', NULL, NULL, NULL, NULL), ('setting.backup.delete', '2', 'Hapus Backup\r\n', NULL, NULL, NULL, NULL), ('setting.backup.download', '2', 'Download Backup', NULL, NULL, NULL, NULL), ('setting.backup.index', '2', 'Lihat Daftar Backup', NULL, NULL, NULL, NULL), ('setting.default.index', '2', 'Lihat Daftar Setting', NULL, NULL, NULL, NULL), ('setting.roles.create', '2', 'Buat Peran', NULL, NULL, NULL, NULL), ('setting.roles.delete', '2', 'Hapus Peran', NULL, NULL, NULL, NULL), ('setting.roles.index', '2', 'Lihat Daftar Peran', NULL, NULL, NULL, NULL), ('setting.roles.update', '2', 'Update Peran', NULL, NULL, NULL, NULL), ('setting.user.create', '2', 'Buat User', NULL, NULL, NULL, NULL), ('setting.user.delete', '2', 'Hapus User', NULL, NULL, NULL, NULL), ('setting.user.index', '2', 'Lihat Daftar User', NULL, NULL, NULL, NULL), ('setting.user.update', '2', 'Update User', NULL, NULL, NULL, NULL);");
        $this->execute("INSERT INTO `auth_item` (`name`, `type`, `description`, `rule_name`, `data`, `created_at`, `updated_at`) VALUES ('setting.profile.myprofile', '2', 'Melihat Profile', NULL, NULL, NULL, NULL);");
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m190918_032331_insert_auth_item_for_setting cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m190918_032331_insert_auth_item_for_setting cannot be reverted.\n";

        return false;
    }
    */
}
