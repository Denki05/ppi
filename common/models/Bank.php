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
 * @property string $bank_type
 * @property string $bank_image
 * @property string $created_on
 * @property string $updated_on
 * @property int $created_by
 * @property int $updated_by
 * @property int $is_deleted
 *
 * @property TblPurchasePaymentDetail[] $tblPurchasePaymentDetails
 * @property TblSalesPaymentDetail[] $tblSalesPaymentDetails
 * @property TblSalesInvoice[] $tblSalesInvoice
 */
class Bank extends \common\models\MasterModel
{
    const BANK_TYPE_PPN = 'ppn';
    const BANK_TYPE_NON_PPN = 'nonppn';
    const BANK_LIST_BCA = 'BCA';
    const BANK_LIST_MANDIRI = 'MANDIRI';
    const BANK_LIST_BRI = 'BRI';
    const BANK_LIST_BNI = 'BNI';
    
    public $imageBank;
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
            [['bank_type'], 'string'],
            [['imageBank'],'file', 'extensions' => 'png,jpg,jpeg', 'maxSize' => 1024000, 'skipOnEmpty' => true],
            [['bank_name', 'bank_acc_name', 'bank_acc_number', 'bank_image', 'bank_note'], 'string', 'max' => 255],
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
            'bank_acc_name' => 'Bank Owner Name',
            'bank_acc_number' => 'Bank Number',
            'bank_type' => 'Bank Type',
            'bank_image' => 'Foto Rekening',
            'bank_note' => 'Catatan',
            'created_on' => 'Created On',
            'updated_on' => 'Updated On',
            'created_by' => 'Created By',
            'updated_by' => 'Updated By',
            'is_deleted' => 'Is Deleted',
            'imageBank' => 'Upload Rekening',
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

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSalesInvoice()
    {
        return $this->hasMany(SalesInvoice::className(), ['bank_id' => 'id']);
    }

    public function getBankName(){
        return $this->bank_acc_name.' - '.$this->bank_name.' '.$this->bank_acc_number;
    }

    public function getBankType($type='')
    {
        $types = [
            self::BANK_TYPE_PPN => 'PPN',
            self::BANK_TYPE_NON_PPN => 'Non PPN',
        ];

        return empty($type) ? $types : (isset($types[$type]) ? $types[$type] : "");
    }

    public function getBankList($type='')
    {
        $types = [
            self::BANK_LIST_BCA => 'BCA',
            self::BANK_LIST_MANDIRI => 'MANDIRI',
            self::BANK_LIST_BRI => 'BRI',
            self::BANK_LIST_BNI => 'BNI',
        ];

        return empty($type) ? $types : (isset($types[$type]) ? $types[$type] : "");
    }

    public function relations()

	{

		// NOTE: you may need to adjust the relation name and the related

		// class name for the relations automatically generated below.

		return array(

                  'sales'=>array(self::HAS_MANY, 'SalesInvoice', 'bank_id'),
		);

	}
}
