<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "tbl_purchase_order".
 *
 * @property int $id
 * @property int $supplier_id
 * @property string $purchase_order_code
 * @property string $purchase_order_date
 * @property string $purchase_order_status
 * @property string $purchase_order_subtotal
 * @property double $purchase_order_disc_percent
 * @property string $purchase_order_disc_amount
 * @property string $purchase_order_grand_total
 * @property string $purchase_order_notes
 * @property string $created_on
 * @property string $updated_on
 * @property int $created_by
 * @property int $updated_by
 * @property int $is_deleted
 *
 * @property TblSupplier $supplier
 * @property TblPurchaseOrderItem[] $tblPurchaseOrderItems
 * @property TblPurchasePayment[] $tblPurchasePayments
 */
class PurchaseOrder extends \common\models\MasterModel
{
    public $supplier_name;
    const STATUS_NEW = 'new';
    const STATUS_PARTIAL = 'partial';
    const STATUS_CLOSE = 'close';
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'tbl_purchase_order';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['supplier_id', 'purchase_order_code', 'purchase_order_date', 'purchase_order_subtotal', 'purchase_order_grand_total'], 'required'],
            [['supplier_id', 'created_by', 'updated_by', 'is_deleted'], 'integer'],
            [['purchase_order_date', 'created_on', 'updated_on', 'supplier_name'], 'safe'],
            [['purchase_order_status', 'purchase_order_notes'], 'string'],
            [['purchase_order_subtotal', 'purchase_order_disc_percent', 'purchase_order_disc_amount', 'purchase_order_grand_total'], 'number'],
            [['purchase_order_code'], 'string', 'max' => 255],
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
            'supplier_id' => 'Supplier',
            'purchase_order_code' => 'Kode Purchase Order',
            'purchase_order_date' => 'Tanggal',
            'purchase_order_status' => 'Status',
            'purchase_order_subtotal' => 'Subtotal',
            'purchase_order_disc_percent' => 'Disc Percent',
            'purchase_order_disc_amount' => 'Disc Tambahan',
            'purchase_order_grand_total' => 'Total Akhir',
            'purchase_order_notes' => 'Catatan',
            'created_on' => 'Created On',
            'updated_on' => 'Updated On',
            'created_by' => 'Created By',
            'updated_by' => 'Updated By',
            'is_deleted' => 'Is Deleted',
            'supplier_name' => 'Supplier',
        ];
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
    public function getPurchaseOrderItems()
    {
        return $this->hasMany(PurchaseOrderItem::className(), ['purchase_order_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPurchasePayments()
    {
        return $this->hasMany(PurchasePayment::className(), ['purchase_order_id' => 'id']);
    }

    public function getStatusLabel($status='')
    {
        $statuses = array(
           self::STATUS_NEW => 'Baru',
            self::STATUS_PARTIAL => 'Sebagian',
            self::STATUS_CLOSE => 'Tutup',
        );
        
        return $status == '' ? $statuses : (isset($statuses[$status]) ? $statuses[$status] : "");
    }

     public function updateStatus($id)
    {
        $amount = (new PurchasePayment)->getPaidAmount($id);
        $po = PurchaseOrder::find()->where('id=:id', [':id' => $id])->one();
        $outstanding = $po->purchase_order_grand_total - $amount;
            
        if ($outstanding == 0){
            $po->purchase_order_status = self::STATUS_CLOSE;
        }
        else if ($outstanding == $po->purchase_order_grand_total){
            $po->purchase_order_status = self::STATUS_NEW;
        }
        else if ($outstanding < $po->purchase_order_grand_total && $outstanding > 0){
            $po->purchase_order_status = self::STATUS_PARTIAL;
        }
        else{
            $po->purchase_order_status = self::STATUS_CLOSE;
        }

        if (!$po->save()) {
                return array(
                    'success' => false,
                    'message' => "Gagal mengupdate status pembayaran PO karena kesalahan berikut: ".ErrorGenerateComponent::generateErrorLabels($invoice->getErrors())
                );
            }
    
            return array(
                'success' => true,
                'message' => '',
            );
    }

    public function isPaymentPO()
    {
        return PurchasePayment::find()->andWhere('purchase_order_id=:id AND is_deleted=:is', [':id' => $this->id, ':is' => 0])->one() ? true : false;
    }
}
