<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "tbl_employee".
 *
 * @property int $id
 * @property int $user_id
 * @property string $employee_name
 * @property string $employee_address
 * @property string $employee_phone
 * @property string $employee_mobile_phone
 * @property string $employee_note
 *
 * @property User $user
 * @property TblSalesInvoice[] $tblSalesInvoices
 */
class Employee extends \common\models\MasterModel
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'tbl_employee';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_id', 'employee_name'], 'required'],
            [['user_id'], 'integer'],
            [['employee_address', 'employee_note'], 'string'],
            [['employee_name'], 'string', 'max' => 255],
            [['employee_phone', 'employee_mobile_phone'], 'string', 'max' => 20],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['user_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_id' => 'User ID',
            'employee_name' => 'Nama Karyawan',
            'employee_address' => 'Alamat Karyawan',
            'employee_phone' => 'No Telepon',
            'employee_mobile_phone' => 'No HP',
            'employee_note' => 'Catatan',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSalesInvoices()
    {
        return $this->hasMany(SalesInvoice::className(), ['salesman_id' => 'id']);
    }

    public function getComissionPays()
    {
        return $this->hasMany(ComissionPay::className(), ['salesman_id' => 'id']);
    }
}
