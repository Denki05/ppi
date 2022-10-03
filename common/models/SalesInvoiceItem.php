<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "tbl_sales_invoice_item".
 *
 * @property int $id
 * @property int $invoice_id
 * @property int $product_id
 * @property double $invoice_item_qty
 * @property string $invoice_item_disc_amount
 * @property double $invoice_item_disc_percent
 * @property string $invoice_item_price
 * @property string $invoice_item_currency
 * @property string $invoice_item_row_total
 *
 * @property TblDeliveryItem[] $tblDeliveryItems
 */
class SalesInvoiceItem extends \common\models\MasterModel
{
    const CURRENCY_DOLAR = 'dolar';
    const CURRENCY_RUPIAH = 'rupiah';
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'tbl_sales_invoice_item';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['invoice_id', 'product_id', 'invoice_item_qty', 'invoice_item_price', 'invoice_item_row_total', 'packaging_id'], 'required'],
            [['invoice_id', 'product_id', 'packaging_id'], 'integer'],
            [['invoice_item_qty', 'invoice_item_disc_amount', 'invoice_item_disc_percent', 'invoice_item_disc_amount2', 'invoice_item_disc_percent2', 'invoice_item_price', 'invoice_item_row_total'], 'number'],
            [['invoice_item_currency'], 'string'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'invoice_id' => 'Invoice ID',
            'product_id' => 'Product',
            'invoice_item_qty' => 'Qty',
            'invoice_item_disc_amount' => 'Diskon',
            'invoice_item_disc_percent' => 'Diskon %',
            'invoice_item_disc_amount2' => 'Diskon 2',
            'invoice_item_disc_percent2' => 'Diskon 2',
            'invoice_item_price' => 'Harga',
            'invoice_item_currency' => 'Currency',
            'invoice_item_row_total' => 'Subtotal',
            'packaging_id' => 'Kemasan',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDeliveryItems()
    {
        return $this->hasMany(DeliveryItem::className(), ['invoice_item_id' => 'id']);
    }

    public function getCurrencyLabel($currency='')
    {
        $currencies = [
            self::CURRENCY_DOLAR => '$',
            self::CURRENCY_RUPIAH => 'Rp',
        ];

        return empty($currency) ? $currencies : (isset($currencies[$currency]) ? $currencies[$currency] : "");
    }

    public function getProduct()
    {
        return $this->hasOne(Product::className(), ['id' => 'product_id']);
    }

    public function getInvoice()
    {
        return $this->hasOne(SalesInvoice::className(), ['id' => 'invoice_id']);
    }

    public function getPackaging()
    {
        return $this->hasOne(Packaging::className(), ['id' => 'packaging_id']);
    }

    public function relations()

  {

    return array(

     'invoice'=>array(self::HAS_MANY,'SalesInvoice','id'),

   );

  }
}
