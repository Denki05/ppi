<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "tbl_purchase_payment_detail".
 *
 * @property int $id
 * @property int $purchase_payment_id
 * @property string $purchase_payment_detail_amount
 * @property string $purchase_payment_detail_method
 * @property int $bank_id
 * @property string $purchase_payment_detail_creditcard_number
 * @property string $purchase_payment_detail_debitcard_number
 * @property string $purchase_payment_detail_bank_acc_number
 * @property string $purchase_payment_detail_bank_acc_name
 *
 * @property TblBank $bank
 * @property TblPurchasePayment $purchasePayment
 */
class PurchasePaymentDetail extends \common\models\MasterModel
{
    const PAY_METHOD_CASH = 'cash';
    const PAY_METHOD_DEBITBCA = 'debitbca';
    const PAY_METHOD_DEBITMANDIRI = 'debitmandiri';
    const PAY_METHOD_DEBITBRI = 'debitbri';
    const PAY_METHOD_DEBITBNI = 'debitbni';
    const PAY_METHOD_BANKTRANSFER = 'banktransfer';
    const PAY_METHOD_CREDITCARD_BCA = 'creditcardbca';
    const PAY_METHOD_CREDITCARD_MANDIRI = 'creditcardmandiri';
    const PAY_METHOD_CREDITCARD_BRI = 'creditcardbri';
    const PAY_METHOD_CREDITCARD_BNI = 'creditcardbni';
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'tbl_purchase_payment_detail';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['purchase_payment_id', 'bank_id'], 'integer'],
            [['purchase_payment_detail_amount', 'purchase_payment_detail_method'], 'required'],
            [['purchase_payment_detail_amount'], 'number'],
            [['purchase_payment_detail_method'], 'string'],
            [['purchase_payment_detail_creditcard_number', 'purchase_payment_detail_debitcard_number', 'purchase_payment_detail_bank_acc_number', 'purchase_payment_detail_bank_acc_name'], 'string', 'max' => 255],
            [['bank_id'], 'exist', 'skipOnError' => true, 'targetClass' => Bank::className(), 'targetAttribute' => ['bank_id' => 'id']],
            [['purchase_payment_id'], 'exist', 'skipOnError' => true, 'targetClass' => PurchasePayment::className(), 'targetAttribute' => ['purchase_payment_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'purchase_payment_id' => 'Purchase Payment ID',
            'purchase_payment_detail_amount' => 'Jumlah Bayar',
            'purchase_payment_detail_method' => 'Cara Bayar',
            'bank_id' => 'Bank',
            'purchase_payment_detail_creditcard_number' => 'Nomor Kartu Kredit',
            'purchase_payment_detail_debitcard_number' => 'Nomor Kartu Debit',
            'purchase_payment_detail_bank_acc_number' => 'Purchase Payment Detail Bank Acc Number',
            'purchase_payment_detail_bank_acc_name' => 'Purchase Payment Detail Bank Acc Name',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBank()
    {
        return $this->hasOne(Bank::className(), ['id' => 'bank_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPurchasePayment()
    {
        return $this->hasOne(PurchasePayment::className(), ['id' => 'purchase_payment_id']);
    }

    public function getPayMethodLabel($method='')
    {
        $methods = [
            self::PAY_METHOD_CASH => 'Cash',
            self::PAY_METHOD_DEBITBCA => 'Debit BCA',
            self::PAY_METHOD_DEBITMANDIRI => 'Debit MANDIRI',
            self::PAY_METHOD_DEBITBRI => 'Debit BRI',
            self::PAY_METHOD_DEBITBNI => 'Debit BNI',
            self::PAY_METHOD_BANKTRANSFER => 'Transfer Bank',
            self::PAY_METHOD_CREDITCARD_BCA => 'Credit Card BCA',
            self::PAY_METHOD_CREDITCARD_MANDIRI => 'Credit Card MANDIRI',
            self::PAY_METHOD_CREDITCARD_BRI => 'Credit Card BRI',
            self::PAY_METHOD_CREDITCARD_BNI => 'Credit Card BNI',
        ];

        return empty($method) ? $methods : (isset($methods[$method]) ? $methods[$method] : "");
    }

    public function getMethodDetail($method)
    {   
        if($method == self::PAY_METHOD_BANKTRANSFER)
            return isset($this->bank->bank_name) ? $this->bank->bank_name.' - '.$this->purchase_payment_detail_bank_acc_name.' '.$this->purchase_payment_detail_bank_acc_number : '';
        elseif($method == self::PAY_METHOD_DEBITBCA || $method == self::PAY_METHOD_DEBITMANDIRI || $method == self::PAY_METHOD_DEBITBRI || $method == self::PAY_METHOD_DEBITBNI)
            return $this->purchase_payment_detail_debitcard_number;
        elseif($method == self::PAY_METHOD_CREDITCARD_BCA || $method == self::PAY_METHOD_CREDITCARD_MANDIRI || $method == self::PAY_METHOD_CREDITCARD_BRI || $method == self::PAY_METHOD_CREDITCARD_BNI)
            return $this->purchase_payment_detail_creditcard_number;
        else
            return '';
    }
}
