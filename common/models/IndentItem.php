<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "tbl_indent_item".
 *
 * @property int $id
 * @property int $indent_id
 * @property int $product_id
 * @property double $qty
 * @property int $indent_item_is_complete
 *
 * @property Indent $indent
 * @property Product $product
 */
class IndentItem extends \common\models\MasterModel
{
    public $indent_date, $product_name, $customer_name, $product_material_code, $product_material_name, $product_code, $factory_name, $brand_name, $category_name;
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'tbl_indent_item';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['indent_id', 'product_id', 'qty'], 'required'],
            [['indent_id', 'product_id', 'indent_item_is_complete'], 'integer'],
            [['qty'], 'number'],
            [['indent_date', 'product_name', 'customer_name', 'product_code', 'product_material_name', 'product_material_code', 'factory_name', 'brand_name', 'category_name'], 'safe'],
            [['indent_id'], 'exist', 'skipOnError' => true, 'targetClass' => Indent::className(), 'targetAttribute' => ['indent_id' => 'id']],
            [['product_id'], 'exist', 'skipOnError' => true, 'targetClass' => Product::className(), 'targetAttribute' => ['product_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'indent_id' => 'Indent ID',
            'product_id' => 'Product ID',
            'qty' => 'Qty',
            'indent_item_is_complete' => 'Selesai',
            'indent_date' => 'Tanggal',
            'product_name' => 'Nama Barang',
            'customer_name' => 'Customer',
            'product_code' => 'Kode Barang',
            'product_material_code' => 'Kode Bahan',
            'product_material_name' => 'Name Bahan',
            'factory_name' => 'Pabrik',
            'brand_name' => 'Merek',
            'category_name' => 'Kategori',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getIndent()
    {
        return $this->hasOne(Indent::className(), ['id' => 'indent_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProduct()
    {
        return $this->hasOne(Product::className(), ['id' => 'product_id']);
    }
}
