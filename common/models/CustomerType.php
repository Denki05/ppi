<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "tbl_customer".
 *
 * @property int $id
 * @property int $code
 * @property int $note
 * @property int $created_at
 * @property int $updated_at
 * @property int $deleted_at
 *
 */
class CustomerType extends \common\models\MasterModel
{
    // const CUSTOMER_TYPE_AGEN = 'AgenPerfumeryTrusted';
    // const CUSTOMER_TYPE_BIGRESELLER = 'BigReseller';
    // const CUSTOMER_TYPE_SMALLRESELLER = 'SmallReseller';
    // // const CUSTOMER_TYPE_GENERALRESELLER = 'generalreseller';
    // const CUSTOMER_TYPE_INDUSTRY = 'industry';
    // const CUSTOMER_TYPE_GENERAL = 'general';
    // const CUSTOMER_TYPE_BIG_PERFUMERY = 'BigPerfumery(Toko/Multicabang)';
    // const CUSTOMER_TYPE_SM_PERFUMERY = 'SmPerfumery(Toko/Multicabang)';
    // const CUSTOMER_TYPE_HOME_INDUSTRI_KOSMETIK = 'HomeIndustriKosmetik';
    // const CUSTOMER_TYPE_HOME_INDUSTRI_PKRT = 'HomeIndustriPkrt';
    // const CUSTOMER_TYPE_INDUSTRI_KOSEMTIK = 'IndustriKosmetik';
    // const CUSTOMER_TYPE_INDUSTRI_PKRT = 'IndustriPkrt';
    // const CUSTOMER_STATUS_ACTIVE = 'active';
    // const CUSTOMER_STATUS_INACTIVE = 'inactive';
    // public $imageCard, $imageNpwp;
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'tbl_customer_type';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['code'], 'required'],
            [['note'], 'string'],
            [['note'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'code' => 'Code Type', 
            'note' => 'Name Type',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCustomer()
    {
        return $this->hasMany(Indent::className(), ['customer_type_id' => 'id']);
    }
}
