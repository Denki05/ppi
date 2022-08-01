<?php

use yii\db\Migration;

/**
 * Class m190913_042328_create_tbl_packaging
 */
class m190913_042328_create_tbl_packaging extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $columns = [
            'id' => $this->primaryKey(),
            'packaging_name' => $this->string(255)->notNull(),
            'is_deleted' => $this->tinyInteger(1)->notNull()->defaultValue(0),
        ];
        $this->createTable('tbl_packaging', $columns);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m190913_042328_create_tbl_packaging cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m190913_042328_create_tbl_packaging cannot be reverted.\n";

        return false;
    }
    */
}
