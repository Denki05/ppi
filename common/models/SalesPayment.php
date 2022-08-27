<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "tbl_sales_payment".
 *
 * @property int $id
 * @property string $payment_code
 * @property string $payment_date
 * @property int $invoice_id
 * @property int $customer_id
 * @property string $payment_exchange_rate
 * @property string $payment_total_amount
 * @property string $payment_notes
 * @property string $created_on
 * @property string $updated_on
 * @property int $created_by
 * @property int $updated_by
 * @property int $is_deleted
 *
 * @property TblCustomer $customer
 * @property TblSalesInvoice $invoice
 * @property TblSalesPaymentDetail[] $tblSalesPaymentDetails
 */
class SalesPayment extends \common\models\MasterModel
{
    public $customer_name, $invoice_code, $salesman_name;
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'tbl_sales_payment';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['payment_code', 'payment_date', 'invoice_id', 'customer_id', 'payment_total_amount'], 'required'],
            [['payment_date', 'created_on', 'updated_on', 'customer_name', 'invoice_code'], 'safe'],
            [['invoice_id', 'customer_id', 'created_by', 'updated_by', 'is_deleted'], 'integer'],
            [['payment_exchange_rate', 'payment_total_amount'], 'number'],
            [['payment_notes'], 'string'],
            [['payment_code'], 'string', 'max' => 255],
            [['customer_id'], 'exist', 'skipOnError' => true, 'targetClass' => Customer::className(), 'targetAttribute' => ['customer_id' => 'id']],
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
            'payment_code' => 'Kode Pembayaran',
            'payment_date' => 'Tanggal Pembayaran',
            'invoice_id' => 'Nomor Nota',
            'customer_id' => 'Customer',
            'payment_exchange_rate' => 'Nilai Kurs',
            'payment_total_amount' => 'Total Pembayaran',
            'payment_notes' => 'Catatan',
            'created_on' => 'Created On',
            'updated_on' => 'Updated On',
            'created_by' => 'Created By',
            'updated_by' => 'Updated By',
            'is_deleted' => 'Is Deleted',
            'customer_name' => 'Customer',
            'invoice_code' => 'Nomer Nota'
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCustomer()
    {
        return $this->hasOne(Customer::className(), ['id' => 'customer_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getInvoice()
    {
        return $this->hasOne(SalesInvoice::className(), ['id' => 'invoice_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSalesPaymentDetails()
    {
        return $this->hasMany(SalesPaymentDetail::className(), ['payment_id' => 'id']);
    }

    public function updateOutstanding($id){
        $invoice = SalesInvoice::find()->where('id=:id', [':id' => $id])->one();
        $invoice->invoice_outstanding_amount = $invoice->invoice_grand_total;
        if (!$invoice->save()) {
            return array(
                'success' => false,
                'message' => "Gagal mengupdate status pembayaran nota karena kesalahan berikut: ".ErrorGenerateComponent::generateErrorLabels($invoice->getErrors())
            );
        }
    
        return array(
            'success' => true,
            'message' => '',
        );
    }

    public function getInvoiceDebtAmount($id, $customerId){
        $invoice = SalesInvoice::find()->where('customer_id = :c AND id != :id AND invoice_payment_status != :p AND is_deleted=:is', [':c' => $customerId, ':id' => $id, ':p' => 'paid', ':is' => 0])->all();

        $outstanding = 0;
        if(!empty($invoice)){
            foreach ($invoice as $item) {
                $outstanding += $item->invoice_outstanding_amount;
            }
        }

        return $outstanding;
    }

     public function getInvoicePaidAmount($id, $customerId){
        $invoice = SalesInvoice::find()->where('customer_id = :c AND id = :id AND invoice_payment_status = :p' , [':c' => $customerId, ':id' => $id, ':p' => 'partial'])->one();

        $paid = 0;
        if(!empty($invoice))
            $paid = $invoice->invoice_grand_total - $invoice->invoice_outstanding_amount;

        return $paid;
    }

    public function currencyRptoUsd($kurs, $amount, $kursInvoice){
        if($kursInvoice > 1)
            return $amount;
        else
            return $amount / $kurs;
    }
}
