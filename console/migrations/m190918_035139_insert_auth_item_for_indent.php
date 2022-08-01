<?php

use yii\db\Migration;

/**
 * Class m190918_035139_insert_auth_item_for_indent
 */
class m190918_035139_insert_auth_item_for_indent extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->execute("INSERT INTO `auth_item` (`name`, `type`, `description`, `rule_name`, `data`, `created_at`, `updated_at`) VALUES ('sales.indent.create', '2', 'Tambah Indent Baru', NULL, NULL, NULL, NULL), ('sales.indent.delete', '2', 'Hapus Indent', NULL, NULL, NULL, NULL), ('sales.indent.index', '2', 'Lihat Daftar Indent', NULL, NULL, NULL, NULL), ('sales.indent.update', '2', 'Edit Indent', NULL, NULL, NULL, NULL);");
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m190918_035139_insert_auth_item_for_indent cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m190918_035139_insert_auth_item_for_indent cannot be reverted.\n";

        return false;
    }
    */
}
