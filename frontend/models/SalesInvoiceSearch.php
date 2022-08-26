<?php

namespace frontend\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\SalesInvoice;

/**
 * SalesInvoiceSearch represents the model behind the search form of `common\models\SalesInvoice`.
 */
class SalesInvoiceSearch extends SalesInvoice
{
    public $customer_name, $salesman_name, $start_date, $end_date, $start_date2, $end_date2, $payment_date, $proses_comission_pay = 0;
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'customer_id', 'bank_id', 'salesman_id', 'comission_type_id', 'created_by', 'updated_by', 'is_deleted'], 'integer'],
            [['invoice_date', 'invoice_code', 'invoice_status', 'invoice_payment_status', 'invoice_comission_pay_date', 'created_on', 'updated_on', 'customer_name', 'salesman_name', 'start_date', 'end_date', 'start_date2', 'end_date2', 'invoice_comission_value', 'payment_date', 'invoice_comission_pay_amount', 'proses_comission_pay'], 'safe'],
            [['invoice_subtotal', 'invoice_disc_amount', 'invoice_disc_percent', 'invoice_tax_amount', 'invoice_tax_percent', 'invoice_grand_total', 'invoice_outstanding_amount', 'invoice_exchange_rate', 'invoice_comission_value'], 'number'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = SalesInvoice::find()->where(['invoice_type' => NULL]);
        $query->joinWith(array('customer', 'salesman', 'salesPayments', 'bank'));

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => (new \common\models\Setting)->getSettingValueByName('record_per_page')
            ],
            'sort'=>[
                'attributes' => [
                    'invoice_code',
                    'invoice_date',
                    'invoice_status',
                    'invoice_payment_status',
                    'invoice_grand_total',
                    'invoice_outstanding_amount',
                    'invoice_comission_value',
                    'payment_date' => [
                        'asc' => ['tbl_sales_payment.payment_date' => SORT_ASC],
                        'desc' => ['tbl_sales_payment.payment_date' => SORT_DESC],
                    ],
                    'customer_name' => [
                        'asc' => ['tbl_customer.customer_name' => SORT_ASC],
                        'desc' => ['tbl_customer.customer_name' => SORT_DESC],
                    ],
                    'salesman_name' => [
                        'asc' => ['tbl_employee.salesman_name' => SORT_ASC],
                        'desc' => ['tbl_employee.salesman_name' => SORT_DESC],
                    ],
                ],
                'defaultOrder' => ['invoice_code' => SORT_DESC],
            ]
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'customer_id' => $this->customer_id,
            'salesman_id' => $this->salesman_id,
            'comission_type_id' => $this->comission_type_id,
            'invoice_date' => $this->invoice_date,
            'invoice_subtotal' => $this->invoice_subtotal,
            'invoice_disc_amount' => $this->invoice_disc_amount,
            'invoice_disc_percent' => $this->invoice_disc_percent,
            'invoice_tax_amount' => $this->invoice_tax_amount,
            'invoice_tax_percent' => $this->invoice_tax_percent,
            'invoice_grand_total' => $this->invoice_grand_total,
            'invoice_outstanding_amount' => $this->invoice_outstanding_amount,
            'invoice_exchange_rate' => $this->invoice_exchange_rate,
            'invoice_comission_value' => $this->invoice_comission_value,
            'invoice_comission_pay_date' => $this->invoice_comission_pay_date,
            'tbl_sales_invoice.is_deleted' => 0,
        ]);

        $query->andFilterWhere(['like', 'invoice_code', $this->invoice_code])
            ->andFilterWhere(['like', 'tbl_customer.customer_name', $this->customer_name])
            ->andFilterWhere(['like', 'tbl_employee.salesman_name', $this->salesman_name]);

        $query->andFilterWhere(['invoice_status' => $this->invoice_status]);
        $query->andFilterWhere(['invoice_payment_status' => $this->invoice_payment_status]);


        if($this->proses_comission_pay == 1)
            $query->andFilterWhere(['is', 'invoice_comission_pay_amount', new \yii\db\Expression('null')]);

        if ($this->start_date != "" && preg_match('/^(\d{2})-(\d{2})-(\d{4})$/', $this->start_date))
            $query->andWhere('DATE(invoice_date) >= "'.date("Y-m-d", strtotime($this->start_date)).'"');
        if ($this->end_date != "" && preg_match('/^(\d{2})-(\d{2})-(\d{4})$/', $this->end_date))
            $query->andWhere('DATE(invoice_date) <= "'.date("Y-m-d", strtotime($this->end_date)).'"');

        if ($this->start_date2 != "" && preg_match('/^(\d{2})-(\d{2})-(\d{4})$/', $this->start_date2))
            $query->andWhere('DATE(tbl_sales_payment.payment_date) >= "'.date("Y-m-d", strtotime($this->start_date2)).'"');
        if ($this->end_date2 != "" && preg_match('/^(\d{2})-(\d{2})-(\d{4})$/', $this->end_date2))
            $query->andWhere('DATE(tbl_sales_payment.payment_date) <= "'.date("Y-m-d", strtotime($this->end_date2)).'"');

        return $dataProvider;
    }
}
