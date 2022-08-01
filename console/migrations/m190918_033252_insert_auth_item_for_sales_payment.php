<?php

use yii\db\Migration;

/**
 * Class m190918_033252_insert_auth_item_for_sales_payment
 */
class m190918_033252_insert_auth_item_for_sales_payment extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->execute("INSERT INTO `auth_item` (`name`, `type`, `description`, `rule_name`, `data`, `created_at`, `updated_at`) VALUES ('sales.salespayment.create', '2', ' \r\nTambah Pembayaran Nota Baru', NULL, NULL, NULL, NULL), ('sales.salespayment.delete', '2', 'Hapus Pembayaran Nota', NULL, NULL, NULL, NULL), ('sales.salespayment.index', '2', 'Lihat Daftar Pembayaran Nota', NULL, NULL, NULL, NULL), ('sales.salespayment.update', '2', 'Edit Pembayaran Nota', NULL, NULL, NULL, NULL), ('sales.salespayment.view', '2', 'Lihat Detil Pembayaran Nota Baru', NULL, NULL, NULL, NULL);");
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m190918_033252_insert_auth_item_for_sales_payment cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m190918_033252_insert_auth_item_for_sales_payment cannot be reverted.\n";

        return false;
    }
    */
}
