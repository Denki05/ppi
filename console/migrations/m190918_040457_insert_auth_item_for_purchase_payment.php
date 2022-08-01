<?php

use yii\db\Migration;

/**
 * Class m190918_040457_insert_auth_item_for_purchase_payment
 */
class m190918_040457_insert_auth_item_for_purchase_payment extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->execute("INSERT INTO `auth_item` (`name`, `type`, `description`, `rule_name`, `data`, `created_at`, `updated_at`) VALUES ('purchase.purchasepayment.create', '2', 'Tambah Pembayaran PO Baru', NULL, NULL, NULL, NULL), ('purchase.purchasepayment.delete', '2', 'Hapus Pembayaran PO', NULL, NULL, NULL, NULL), ('purchase.purchasepayment.index', '2', 'Lihat Daftar Pembayaran PO', NULL, NULL, NULL, NULL), ('purchase.purchasepayment.view', '2', 'Lihat Detil Pembayaran PO', NULL, NULL, NULL, NULL), ('purchase.purchasepayment.update', '2', 'Edit Pembayaran PO', NULL, NULL, NULL, NULL);");
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m190918_040457_insert_auth_item_for_purchase_payment cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m190918_040457_insert_auth_item_for_purchase_payment cannot be reverted.\n";

        return false;
    }
    */
}
