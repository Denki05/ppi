<?php

namespace frontend\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\SalesPayment;

/**
 * SalesPaymentSearch represents the model behind the search form of `common\models\SalesPayment`.
 */
class SalesPaymentSearch extends SalesPayment
{  
    public $customer_name, $invoice_code, $start_date, $end_date;
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'invoice_id', 'customer_id', 'created_by', 'updated_by', 'is_deleted'], 'integer'],
            [['payment_code', 'payment_date', 'payment_notes', 'created_on', 'updated_on', 'customer_name', 'invoice_code', 'start_date', 'end_date'], 'safe'],
            [['payment_exchange_rate', 'payment_total_amount'], 'number'],
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
        $query = SalesPayment::find();
        $query->joinWith(array('invoice', 'customer'));

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => (new \common\models\Setting)->getSettingValueByName('record_per_page')
            ],
            'sort'=>[
                'attributes' => [
                    'payment_date',
                    'payment_code',
                    'customer_name' => [
                        'asc' => ['tbl_customer.customerName' => SORT_ASC],
                        'desc' => ['tbl_customer.customerName' => SORT_DESC],
                    ],
                    'invoice_code' => [
                        'asc' => ['tbl_sales_invoice.invoice_code' => SORT_ASC],
                        'desc' => ['tbl_sales_invoice.invoice_code' => SORT_DESC],
                    ],
                    'payment_total_amount',
                    'payment_notes',
                    'tbl_sales_payment.id',
                ],
                'defaultOrder' => ['payment_date' => SORT_DESC, 'tbl_sales_payment.id'=> SORT_DESC],
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
            // 'payment_date' => $this->payment_date,
            'invoice_id' => $this->invoice_id,
            'customer_id' => $this->customer_id,
            'payment_exchange_rate' => $this->payment_exchange_rate,
            'payment_total_amount' => $this->payment_total_amount,
            'created_on' => $this->created_on,
            'updated_on' => $this->updated_on,
            'created_by' => $this->created_by,
            'updated_by' => $this->updated_by,
            'tbl_sales_payment.is_deleted' => 0,
        ]);

        $query->andFilterWhere(['like', 'payment_code', $this->payment_code])
            ->andFilterWhere(['like', 'payment_notes', $this->payment_notes])
            ->andFilterWhere(['like', 'tbl_customer.customerName', $this->customer_name])
            ->andFilterWhere(['like', 'tbl_sales_invoice.invoice_code', $this->invoice_code]);

        if ($this->start_date != "" && preg_match('/^(\d{2})-(\d{2})-(\d{4})$/', $this->start_date))
            $query->andWhere('DATE(payment_date) >= "'.date("Y-m-d", strtotime($this->start_date)).'"');
        if ($this->end_date != "" && preg_match('/^(\d{2})-(\d{2})-(\d{4})$/', $this->end_date))
            $query->andWhere('DATE(payment_date) <= "'.date("Y-m-d", strtotime($this->end_date)).'"');

        return $dataProvider;
    }
}
