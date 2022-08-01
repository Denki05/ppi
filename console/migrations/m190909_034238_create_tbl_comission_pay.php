<?php

use yii\db\Migration;

/**
 * Class m190909_034238_create_tbl_comission_pay
 */
class m190909_034238_create_tbl_comission_pay extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $columns = [
            'id' => $this->primaryKey(),
            'salesman_id' => $this->integer()->notNull(),
            'comission_pay_date' => $this->date()->notNull(),
            'comission_pay_exchange_rate' => $this->decimal(16,2)->notNull(),
            'comission_pay_periode' => "ENUM('maret','juni', 'september', 'desember') NOT NULL",
            'comission_pay_value' => $this->decimal(16,2)->notNull(),
            'created_by' => $this->integer(),
            'updated_by' => $this->integer(),
            'created_on' => $this->dateTime(),
            'updated_on' => $this->dateTime(),
            'is_deleted' => $this->tinyInteger(1)->notNull()->defaultValue(0),
        ];
        $this->createTable('tbl_comission_pay', $columns);

        $this->addForeignKey('FK_tbl_comission_pay_tbl_employee', 'tbl_comission_pay', 'salesman_id', 'tbl_employee', 'id', 'NO ACTION', 'NO ACTION');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m190909_034238_create_tbl_comission_pay cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m190909_034238_create_tbl_comission_pay cannot be reverted.\n";

        return false;
    }
    */
}
