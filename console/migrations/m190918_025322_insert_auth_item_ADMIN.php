<?php

use yii\db\Migration;

/**
 * Class m190918_025322_insert_auth_item_ADMIN
 */
class m190918_025322_insert_auth_item_ADMIN extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->execute("INSERT INTO `auth_item` (`name`, `type`, `description`, `rule_name`, `data`, `created_at`, `updated_at`) VALUES ('ADMIN', '1', 'ADMIN', NULL, NULL, NULL, NULL);");
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m190918_025322_insert_auth_item_ADMIN cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m190918_025322_insert_auth_item_ADMIN cannot be reverted.\n";

        return false;
    }
    */
}
