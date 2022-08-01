<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "tbl_purchase_order_item".
 *
 * @property int $id
 * @property int $purchase_order_id
 * @property int $product_id
 * @property double $purchase_order_item_qty
 * @property string $purchase_order_item_price
 * @property double $purchase_order_item_disc_percent
 * @property string $purchase_order_item_disc_amount
 * @property string $purchase_order_item_row_total
 *
 * @property TblPurchaseOrder $purchaseOrder
 */
class PurchaseOrderItem extends \common\models\MasterModel
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'tbl_purchase_order_item';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['purchase_order_id', 'product_id', 'purchase_order_item_qty', 'purchase_order_item_price', 'purchase_order_item_row_total'], 'required'],
            [['purchase_order_id', 'product_id'], 'integer'],
            [['purchase_order_item_qty', 'purchase_order_item_price', 'purchase_order_item_disc_percent', 'purchase_order_item_disc_amount', 'purchase_order_item_row_total'], 'number'],
            [['purchase_order_id'], 'exist', 'skipOnError' => true, 'targetClass' => PurchaseOrder::className(), 'targetAttribute' => ['purchase_order_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'purchase_order_id' => 'Purchase Order ID',
            'product_id' => 'Barang',
            'purchase_order_item_qty' => 'Qty',
            'purchase_order_item_price' => 'Harga',
            'purchase_order_item_disc_percent' => 'Disc %',
            'purchase_order_item_disc_amount' => 'Disc',
            'purchase_order_item_row_total' => 'Subtotal',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPurchaseOrder()
    {
        return $this->hasOne(PurchaseOrder::className(), ['id' => 'purchase_order_id']);
    }

    public function getProduct()
    {
        return $this->hasOne(Product::className(), ['id' => 'product_id']);
    }
}
