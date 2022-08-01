<?php

use yii\db\Migration;

/**
 * Class m190918_033458_insert_auth_item_for_sales_invoice_khusus
 */
class m190918_033458_insert_auth_item_for_sales_invoice_khusus extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->execute("INSERT INTO `auth_item` (`name`, `type`, `description`, `rule_name`, `data`, `created_at`, `updated_at`) VALUES ('sales.salesinvoice.create', '2', 'Tambah Nota Khusus Baru', NULL, NULL, NULL, NULL), ('sales.salesinvoice.index', '2', 'Lihat Daftar Nota Khusus', NULL, NULL, NULL, NULL), ('sales.salesinvoice.delete', '2', 'Hapus Nota Khusus', NULL, NULL, NULL, NULL), ('sales.salesinvoice.update', '2', 'Edit Nota Khusus', NULL, NULL, NULL, NULL), ('sales.salesinvoice.view', '2', 'Lihat Detil Nota Khusus', NULL, NULL, NULL, NULL);");
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m190918_033458_insert_auth_item_for_sales_invoice_khusus cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m190918_033458_insert_auth_item_for_sales_invoice_khusus cannot be reverted.\n";

        return false;
    }
    */
}
