<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "tbl_indent".
 *
 * @property int $id
 * @property string $indent_date
 * @property int $customer_id
 * @property int $created_by
 * @property int $updated_by
 * @property string $created_on
 * @property string $updated_on
 * @property int $is_deleted
 *
 * @property Customer $customer
 * @property IndentItem[] $indentItems
 */
class Indent extends \common\models\MasterModel
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'tbl_indent';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['indent_date', 'customer_id'], 'required'],
            [['indent_date', 'created_on', 'updated_on'], 'safe'],
            [['customer_id', 'created_by', 'updated_by', 'is_deleted'], 'integer'],
            [['customer_id'], 'exist', 'skipOnError' => true, 'targetClass' => Customer::className(), 'targetAttribute' => ['customer_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'indent_date' => 'Tanggal',
            'customer_id' => 'Customer',
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
    public function getCustomer()
    {
        return $this->hasOne(Customer::className(), ['id' => 'customer_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getIndentItems()
    {
        return $this->hasMany(IndentItem::className(), ['indent_id' => 'id']);
    }
}
