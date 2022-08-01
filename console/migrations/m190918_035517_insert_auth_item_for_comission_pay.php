<?php

use yii\db\Migration;

/**
 * Class m190918_035517_insert_auth_item_for_comission_pay
 */
class m190918_035517_insert_auth_item_for_comission_pay extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->execute("INSERT INTO `auth_item` (`name`, `type`, `description`, `rule_name`, `data`, `created_at`, `updated_at`) VALUES ('sales.comissionpay.create', '2', 'Tambah Pencairan Komisi Baru', NULL, NULL, NULL, NULL), ('sales.comissionpay.delete', '2', 'Hapus Pencairan Komisi', NULL, NULL, NULL, NULL), ('sales.comissionpay.index', '2', 'Lihat Daftar Pencairan Komisi', NULL, NULL, NULL, NULL), ('sales.comissionpay.view', '2', 'Lihat Detil Pencairan Komisi', NULL, NULL, NULL, NULL), ('sales.comissionpay.update', '2', 'Edit Pencairan Komisi', NULL, NULL, NULL, NULL);");
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m190918_035517_insert_auth_item_for_comission_pay cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m190918_035517_insert_auth_item_for_comission_pay cannot be reverted.\n";

        return false;
    }
    */
}
