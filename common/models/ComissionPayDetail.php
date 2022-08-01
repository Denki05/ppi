<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "tbl_comission_pay_detail".
 *
 * @property int $id
 * @property int $comission_pay_id
 * @property int $invoice_id
 * @property double $comission_pay_detail_percent
 * @property string $comission_pay_detail_amount
 *
 * @property TblComissionPay $comissionPay
 * @property TblSalesInvoice $invoice
 */
class ComissionPayDetail extends \common\models\MasterModel
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'tbl_comission_pay_detail';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['comission_pay_id', 'invoice_id', 'comission_pay_detail_percent', 'comission_pay_detail_amount'], 'required'],
            [['comission_pay_id', 'invoice_id'], 'integer'],
            [['comission_pay_detail_percent', 'comission_pay_detail_amount'], 'number'],
            [['comission_pay_id'], 'exist', 'skipOnError' => true, 'targetClass' => ComissionPay::className(), 'targetAttribute' => ['comission_pay_id' => 'id']],
            [['invoice_id'], 'exist', 'skipOnError' => true, 'targetClass' => SalesInvoice::className(), 'targetAttribute' => ['invoice_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'comission_pay_id' => 'Comission Pay ID',
            'invoice_id' => 'Invoice ID',
            'comission_pay_detail_percent' => 'Comission Pay Detail Percent',
            'comission_pay_detail_amount' => 'Comission Pay Detail Amount',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getComissionPay()
    {
        return $this->hasOne(ComissionPay::className(), ['id' => 'comission_pay_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getInvoice()
    {
        return $this->hasOne(SalesInvoice::className(), ['id' => 'invoice_id']);
    }
}
