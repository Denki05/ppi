<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "tbl_brand".
 *
 * @property int $id
 * @property string $brand_name
 * @property string $brand_type original - merk dari sononya | ppi - merk buatan ppi sendiri
 * @property int $created_by
 * @property int $updated_by
 * @property string $created_on
 * @property string $updated_on
 * @property int $is_deleted
 *
 * @property TblCategory[] $tblCategories
 * @property TblProduct[] $tblProducts
 * @property TblProduct[] $tblProducts0
 */
class Brand extends \common\models\MasterModel
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'tbl_brand';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['brand_name', 'brand_type'], 'required'],
            [['id', 'created_by', 'updated_by', 'is_deleted'], 'integer'],
            [['brand_type'], 'string'],
            [['created_on', 'updated_on'], 'safe'],
            [['brand_name'], 'string', 'max' => 255],
            [['id'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'brand_name' => 'Brand Name',
            'brand_type' => 'Brand Type',
            'created_by' => 'Created By',
            'updated_by' => 'Updated By',
            'created_on' => 'Created On',
            'updated_on' => 'Updated On',
            'is_deleted' => 'Is Deleted',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTblCategories()
    {
        return $this->hasMany(Category::className(), ['brand_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTblProducts()
    {
        return $this->hasMany(Product::className(), ['brand_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTblProducts0()
    {
        return $this->hasMany(Product::className(), ['original_brand_id' => 'id']);
    }
}
