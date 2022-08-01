<?php

use yii\db\Migration;

/**
 * Class m190918_040108_insert_auth_item_for_purchase_order
 */
class m190918_040108_insert_auth_item_for_purchase_order extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->execute("INSERT INTO `auth_item` (`name`, `type`, `description`, `rule_name`, `data`, `created_at`, `updated_at`) VALUES ('purchase.purchaseorder.create', '2', 'Tambah PO Baru', NULL, NULL, NULL, NULL), ('purchase.purchaseorder.delete', '2', 'Hapus PO', NULL, NULL, NULL, NULL), ('purchase.purchaseorder.index', '2', 'Lihat Daftar PO', NULL, NULL, NULL, NULL), ('purchase.purchaseorder.view', '2', 'Lihat Detil PO', NULL, NULL, NULL, NULL), ('purchase.purchaseorder.update', '2', 'Edit PO', NULL, NULL, NULL, NULL);");
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m190918_040108_insert_auth_item_for_purchase_order cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m190918_040108_insert_auth_item_for_purchase_order cannot be reverted.\n";

        return false;
    }
    */
}
