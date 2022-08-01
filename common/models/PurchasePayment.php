<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "tbl_purchase_payment".
 *
 * @property int $id
 * @property string $purchase_payment_code
 * @property int $purchase_order_id
 * @property int $supplier_id
 * @property string $purchase_payment_date
 * @property string $purchase_payment_total_amount
 * @property string $purchase_payment_method
 * @property int $created_by
 * @property int $updated_by
 * @property string $updated_on
 * @property string $created_on
 * @property int $is_deleted
 *
 * @property TblPurchaseOrder $purchaseOrder
 * @property TblSupplier $supplier
 * @property TblPurchasePaymentDetail[] $tblPurchasePaymentDetails
 */
class PurchasePayment extends \common\models\MasterModel
{
    public $purchase_order_code;
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'tbl_purchase_payment';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['purchase_payment_code', 'purchase_order_id', 'supplier_id', 'purchase_payment_date', 'purchase_payment_total_amount'], 'required'],
            [['purchase_order_id', 'supplier_id', 'created_by', 'updated_by', 'is_deleted'], 'integer'],
            [['purchase_payment_date', 'updated_on', 'created_on'], 'safe'],
            [['purchase_payment_total_amount'], 'number'],
            [['purchase_payment_method', 'purchase_order_code'], 'safe'],
            [['purchase_payment_method'], 'string'],
            [['purchase_payment_code'], 'string', 'max' => 255],
            [['purchase_order_id'], 'exist', 'skipOnError' => true, 'targetClass' => PurchaseOrder::className(), 'targetAttribute' => ['purchase_order_id' => 'id']],
            [['supplier_id'], 'exist', 'skipOnError' => true, 'targetClass' => Supplier::className(), 'targetAttribute' => ['supplier_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'purchase_payment_code' => 'Kode Pembayaran',
            'purchase_order_id' => 'Nomer PO',
            'supplier_id' => 'Supplier',
            'purchase_payment_date' => 'Tanggal',
            'purchase_payment_total_amount' => 'Total Pembayaran',
            'purchase_payment_method' => 'Cara Bayar',
            'created_by' => 'Created By',
            'updated_by' => 'Updated By',
            'updated_on' => 'Updated On',
            'created_on' => 'Created On',
            'is_deleted' => 'Is Deleted',
            'purchase_order_code' => 'Nomer PO',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPurchaseOrder()
    {
        return $this->hasOne(PurchaseOrder::className(), ['id' => 'purchase_order_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSupplier()
    {
        return $this->hasOne(Supplier::className(), ['id' => 'supplier_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPurchasePaymentDetails()
    {
        return $this->hasMany(PurchasePaymentDetail::className(), ['purchase_payment_id' => 'id']);
    }

    
    public function getPaidAmount($id){
        
        $payment = PurchasePayment::find()->where('purchase_order_id = :id AND is_deleted=:is' , [':id' => $id, ':is' => 0 ])->all();
        
        $pay = 0;
        if(!empty($payment)){
            foreach ($payment as $item) {
                $pay += $item->purchase_payment_total_amount;
            }
        }

        return $pay;
    }
}
