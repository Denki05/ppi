<?php

use yii\db\Migration;

/**
 * Class m190910_082318_create_tbl_comission_pay_detail
 */
class m190910_082318_create_tbl_comission_pay_detail extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $columns = [
            'id' => $this->primaryKey(),
            'comission_pay_id' => $this->integer()->notNull(),
            'invoice_id' => $this->integer()->notNull(),
            'comission_pay_detail_percent' => $this->float()->notNull(),
            'comission_pay_detail_amount' => $this->decimal(16,2)->notNull(),
        ];
        $this->createTable('tbl_comission_pay_detail', $columns);
        $this->addForeignKey('FK_tbl_comission_pay_detail_tbl_comission_pay', 'tbl_comission_pay_detail', 'comission_pay_id', 'tbl_comission_pay', 'id', 'NO ACTION', 'NO ACTION');
        $this->addForeignKey('FK_tbl_comission_pay_detail_tbl_invoice', 'tbl_comission_pay_detail', 'invoice_id', 'tbl_sales_invoice', 'id', 'NO ACTION', 'NO ACTION');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m190910_082318_create_tbl_comission_pay_detail cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m190910_082318_create_tbl_comission_pay_detail cannot be reverted.\n";

        return false;
    }
    */
}
