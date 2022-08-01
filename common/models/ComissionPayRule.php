<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "tbl_comission_pay_rule".
 *
 * @property int $id
 * @property int $comission_pay_rule_start_day
 * @property int $comission_pay_rule_end_day
 * @property double $comission_pay_rule_value
 * @property int $created_by
 * @property int $updated_by
 * @property string $created_on
 * @property string $updated_on
 * @property int $is_deleted
 */
class ComissionPayRule extends \common\models\MasterModel
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'tbl_comission_pay_rule';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['comission_pay_rule_start_day', 'comission_pay_rule_end_day', 'comission_pay_rule_value'], 'required'],
            [['comission_pay_rule_start_day', 'comission_pay_rule_end_day', 'created_by', 'updated_by', 'is_deleted'], 'integer'],
            [['comission_pay_rule_value'], 'number'],
            [['created_on', 'updated_on'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'comission_pay_rule_start_day' => 'Mulai',
            'comission_pay_rule_end_day' => 'Selesai',
            'comission_pay_rule_value' => 'Jumlah',
            'created_by' => 'Created By',
            'updated_by' => 'Updated By',
            'created_on' => 'Created On',
            'updated_on' => 'Updated On',
            'is_deleted' => 'Is Deleted',
        ];
    }
}
