<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "tbl_bank".
 *
 * @property int $id
 * @property string $invoice_id
 * @property string $invoice_code
 * @property string $created_on
 * @property string $updated_on
 * @property int $created_by
 * @property int $updated_by
 * @property int $is_deleted
 *
 * @property TblSalesInvoice[] $tblSalesInvoice
 */
class InvoiceNote extends \common\models\MasterModel
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'tbl_invoice_note';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['invoice_id'], 'required'],
            [['created_on', 'updated_on'], 'safe'],
            [['created_by', 'updated_by', 'is_deleted'], 'integer'],
            [['invoice_code'], 'string', 'max' => 255],
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
    public function getInvoice()
    {
        return $this->hasMany(SalesInvoice::className(), ['invoice_id' => 'id']);
    }

    public function relations()

	{

		// NOTE: you may need to adjust the relation name and the related

		// class name for the relations automatically generated below.

		return array(

                  'invoice'=>array(self::HAS_MANY, 'SalesInvoice', 'invoice_id'),
		);

	}
}
