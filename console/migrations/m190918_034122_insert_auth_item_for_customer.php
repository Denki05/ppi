<?php

use yii\db\Migration;

/**
 * Class m190918_034122_insert_auth_item_for_customer
 */
class m190918_034122_insert_auth_item_for_customer extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->execute("INSERT INTO `auth_item` (`name`, `type`, `description`, `rule_name`, `data`, `created_at`, `updated_at`) VALUES ('customer.default.create', '2', 'Tambah Customer Baru', NULL, NULL, NULL, NULL), ('customer.default.delete', '2', 'Hapus Customer', NULL, NULL, NULL, NULL), ('customer.default.index', '2', 'Lihat Daftar Customer', NULL, NULL, NULL, NULL), ('customer.default.view', '2', 'Lihat Detil Customer', NULL, NULL, NULL, NULL), ('customer.default.update', '2', 'Edit Customer', NULL, NULL, NULL, NULL);");
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m190918_034122_insert_auth_item_for_customer cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m190918_034122_insert_auth_item_for_customer cannot be reverted.\n";

        return false;
    }
    */
}
