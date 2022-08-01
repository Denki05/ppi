<?php

use yii\db\Migration;

/**
 * Class m190918_033823_insert_auth_item_for_sales_invoice
 */
class m190918_033823_insert_auth_item_for_sales_invoice extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->execute("INSERT INTO `auth_item` (`name`, `type`, `description`, `rule_name`, `data`, `created_at`, `updated_at`) VALUES ('sales.salesinvoicespecial.create', '2', 'Tambah Nota Baru', NULL, NULL, NULL, NULL), ('sales.salesinvoicespecial.index', '2', 'Lihat Daftar Nota', NULL, NULL, NULL, NULL), ('sales.salesinvoicespecial.delete', '2', 'Hapus Nota', NULL, NULL, NULL, NULL), ('sales.salesinvoicespecial.update', '2', 'Edit Nota', NULL, NULL, NULL, NULL), ('sales.salesinvoicespecial.view', '2', 'Lihat Detil Nota', NULL, NULL, NULL, NULL);");
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m190918_033823_insert_auth_item_for_sales_invoice cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m190918_033823_insert_auth_item_for_sales_invoice cannot be reverted.\n";

        return false;
    }
    */
}
