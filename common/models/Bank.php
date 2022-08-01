<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "tbl_bank".
 *
 * @property int $id
 * @property string $bank_name
 * @property string $bank_acc_name
 * @property string $bank_acc_number
 * @property string $created_on
 * @property string $updated_on
 * @property int $created_by
 * @property int $updated_by
 * @property int $is_deleted
 *
 * @property TblPurchasePaymentDetail[] $tblPurchasePaymentDetails
 * @property TblSalesPaymentDetail[] $tblSalesPaymentDetails
 */
class Bank extends \common\models\MasterModel
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'tbl_bank';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['bank_name', 'bank_acc_name', 'bank_acc_number'], 'required'],
            [['created_on', 'updated_on'], 'safe'],
            [['created_by', 'updated_by', 'is_deleted'], 'integer'],
            [['bank_name', 'bank_acc_name', 'bank_acc_number'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'bank_name' => 'Bank Name',
            'bank_acc_name' => 'Bank Acc Name',
            'bank_acc_number' => 'Bank Acc Number',
            'created_on' => 'Created On',
            'updated_on' => 'Updated On',
            'created_by' => 'Created By',
            'updated_by' => 'Updated By',
            'is_deleted' => 'Is Deleted',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPurchasePaymentDetails()
    {
        return $this->hasMany(PurchasePaymentDetail::className(), ['bank_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSalesPaymentDetails()
    {
        return $this->hasMany(SalesPaymentDetail::className(), ['bank_id' => 'id']);
    }

    public function getBankName(){
        return $this->bank_acc_name.' - '.$this->bank_name.' '.$this->bank_acc_number;
    }
}
