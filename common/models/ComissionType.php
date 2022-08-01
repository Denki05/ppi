<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "tbl_comission_type".
 *
 * @property int $id
 * @property string $comission_type_name
 * @property string $comission_type_desc
 * @property double $comission_type_value
 * @property string $created_on
 * @property string $updated_on
 * @property int $created_by
 * @property int $updated_by
 * @property int $is_deleted
 *
 * @property TblSalesInvoice[] $tblSalesInvoices
 */
class ComissionType extends \common\models\MasterModel
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'tbl_comission_type';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['comission_type_name', 'comission_type_desc', 'comission_type_value'], 'required'],
            [['comission_type_desc'], 'string'],
            [['comission_type_value'], 'number'],
            [['created_on', 'updated_on'], 'safe'],
            [['created_by', 'updated_by', 'is_deleted'], 'integer'],
            [['comission_type_name'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'comission_type_name' => 'Nama Komisi',
            'comission_type_desc' => 'Keterangan',
            'comission_type_value' => 'Nilai Komisi(%)',
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
    public function getSalesInvoices()
    {
        return $this->hasMany(SalesInvoice::className(), ['comission_type_id' => 'id']);
    }
}
