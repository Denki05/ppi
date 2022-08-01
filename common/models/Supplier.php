<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "tbl_supplier".
 *
 * @property int $id
 * @property string $supplier_code
 * @property string $supplier_name
 * @property string $supplier_address
 * @property string $supplier_phone1
 * @property string $supplier_phone2
 * @property int $created_by
 * @property int $created_on
 * @property int $updated_by
 * @property int $updated_on
 * @property int $is_deleted
 *
 * @property TblPurchaseOrder[] $tblPurchaseOrders
 * @property TblPurchasePayment[] $tblPurchasePayments
 */
class Supplier extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'tbl_supplier';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['supplier_name'], 'required'],
            [['supplier_address'], 'string'],
            [['created_by', 'created_on', 'updated_by', 'updated_on', 'is_deleted'], 'integer'],
            [['supplier_code', 'supplier_name', 'supplier_phone1', 'supplier_phone2'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'supplier_code' => 'Supplier Code',
            'supplier_name' => 'Supplier Name',
            'supplier_address' => 'Supplier Address',
            'supplier_phone1' => 'Supplier Phone1',
            'supplier_phone2' => 'Supplier Phone2',
            'created_by' => 'Created By',
            'created_on' => 'Created On',
            'updated_by' => 'Updated By',
            'updated_on' => 'Updated On',
            'is_deleted' => 'Is Deleted',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTblPurchaseOrders()
    {
        return $this->hasMany(TblPurchaseOrder::className(), ['supplier_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTblPurchasePayments()
    {
        return $this->hasMany(TblPurchasePayment::className(), ['supplier_id' => 'id']);
    }
}
