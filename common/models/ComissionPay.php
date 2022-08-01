<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "tbl_comission_pay".
 *
 * @property int $id
 * @property int $salesman_id
 * @property string $comission_pay_date
 * @property string $comission_pay_exchange_rate
 * @property string $comission_pay_periode
 * @property string $comission_pay_value
 * @property int $created_by
 * @property int $updated_by
 * @property string $created_on
 * @property string $updated_on
 * @property int $is_deleted
 *
 * @property TblEmployee $salesman
 */
class ComissionPay extends \common\models\MasterModel
{
    public $salesman_name;
    const PERIODE_MARET = 'maret';
    const PERIODE_JUNI = 'juni';
    const PERIODE_SEPTEMBER = 'september';
    const PERIODE_DESEMBER = 'desember';
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'tbl_comission_pay';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['salesman_id', 'comission_pay_date', 'comission_pay_exchange_rate', 'comission_pay_periode', 'comission_pay_value'], 'required'],
            [['salesman_id', 'created_by', 'updated_by', 'is_deleted'], 'integer'],
            [['comission_pay_date', 'created_on', 'updated_on', 'salesman_name'], 'safe'],
            [['comission_pay_exchange_rate', 'comission_pay_value'], 'number'],
            [['comission_pay_periode'], 'string'],
            [['salesman_id'], 'exist', 'skipOnError' => true, 'targetClass' => Employee::className(), 'targetAttribute' => ['salesman_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'salesman_id' => 'Sales',
            'comission_pay_date' => 'Tanggal Cair',
            'comission_pay_exchange_rate' => 'Kurs',
            'comission_pay_periode' => 'Periode',
            'comission_pay_value' => 'Jumlah Komisi',
            'created_by' => 'Created By',
            'updated_by' => 'Updated By',
            'created_on' => 'Created On',
            'updated_on' => 'Updated On',
            'is_deleted' => 'Is Deleted',
            'salesman_name' => 'Sales',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSalesman()
    {
        return $this->hasOne(Employee::className(), ['id' => 'salesman_id']);
    }

    public function getComissionPayDetails()
    {
        return $this->hasMany(ComissionPayDetail::className(), ['comission_pay_id' => 'id']);
    }

     public function getPeriodeLabel($period='')
    {
        $periods = [
            self::PERIODE_MARET => 'Maret',
            self::PERIODE_JUNI => 'Juni',
            self::PERIODE_SEPTEMBER => 'September',
            self::PERIODE_DESEMBER => 'Desember',
        ];

        return empty($period) ? $periods : (isset($periods[$period]) ? $periods[$period] : "");
    }

    public function getComissionNotPay($id, $periode, $y){

        if($periode == 'maret'){
            $start = date($y."-01-01");
            $end = date($y."-03-t");
        }
        elseif($periode == 'juni'){
            $start = date($y."-04-01");
            $end = date($y."-06-t");
        }
        if($periode == 'september'){
            $start = date($y."-07-01");
            $end = date($y."-09-t");
        }
        else{
            $start = date($y."-10-01");
            $end = date($y."-12-t");
        }

        $invoice = SalesInvoice::find()->andWhere('salesman_id=:s_id AND is_deleted=:is AND invoice_payment_status=:status', [':s_id' => $id, ':is' => 0, ':status'=> 'paid'])
        ->andWhere(['between', 'invoice_date', $start, $end])
        ->andWhere('tbl_sales_invoice.id not in 
            (select 
                b.invoice_id 
            from 
                tbl_comission_pay_detail b,
                tbl_comission_pay a
            where 
            b.comission_pay_id = a.id AND
            a.is_deleted = 0 AND
            b.invoice_id = tbl_sales_invoice.id AND 
            a.comission_pay_date) AND comission_type_id IS NOT NULL', array())->all();
        $total = 0;
        foreach ($invoice as $item) {
            $total = $item->invoice_grand_total * $item->comissionType->comission_type_value / 100;
        }

        return $total;
    }

    public function getComissionPaid($id, $periode, $y){
        if($periode == 'maret'){
            $start = date($y."-01-01");
            $end = date($y."-03-t");
        }
        elseif($periode == 'juni'){
            $start = date($y."-04-01");
            $end = date($y."-06-t");
        }
        if($periode == 'september'){
            $start = date($y."-07-01");
            $end = date($y."-09-t");
        }
        else{
            $start = date($y."-10-01");
            $end = date($y."-12-t");
        }

        $invoice = SalesInvoice::find()->andWhere('salesman_id=:s_id AND is_deleted=:is AND invoice_payment_status=:status', [':s_id' => $id, ':is' => 0, ':status'=> 'paid'])
        ->andWhere(['between', 'invoice_date', $start, $end])
        ->andWhere('tbl_sales_invoice.id in 
            (select 
                b.invoice_id 
            from 
                tbl_comission_pay_detail b,
                tbl_comission_pay a
            where 
            b.comission_pay_id = a.id AND
            a.is_deleted = 0 AND
            b.invoice_id = tbl_sales_invoice.id AND 
            a.comission_pay_date)', array())->all();
        $total = 0;
        foreach ($invoice as $item) {
            $total = $item->invoice_grand_total * $item->comissionType->comission_type_value / 100;
        }

        return $total;
    }
}
