<?php

namespace frontend\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\PurchasePayment;

/**
 * PurchasePaymentSearch represents the model behind the search form of `common\models\PurchasePayment`.
 */
class PurchasePaymentSearch extends PurchasePayment
{
     public $purchase_order_code, $start_date, $end_date;
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'purchase_order_id', 'created_by', 'updated_by', 'is_deleted'], 'integer'],
            [['purchase_payment_code', 'purchase_payment_date', 'purchase_payment_method', 'updated_on', 'created_on', 'purchase_order_code', 'start_date', 'end_date'], 'safe'],
            [['purchase_payment_total_amount'], 'number'],
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
        $query = PurchasePayment::find();
        $query->joinWith('purchaseOrder');

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => (new \common\models\Setting)->getSettingValueByName('record_per_page')
            ],
            'sort'=>[
                'attributes' => [
                    'purchase_payment_date',
                    'purchase_payment_code',
                    'purchase_payment_total_amount',
                    'purchase_order_code' => [
                        'asc' => ['tbl_purchase_order.purchase_order_code' => SORT_ASC],
                        'desc' => ['tbl_purchase_order.purchase_order_code' => SORT_DESC],
                    ],
                ],
                // 'defaultOrder' => ['id' => SORT_DESC],
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
            'purchase_order_id' => $this->purchase_order_id,
            // 'purchase_payment_date' => $this->purchase_payment_date,
            'purchase_payment_total_amount' => $this->purchase_payment_total_amount,
            'created_by' => $this->created_by,
            'updated_by' => $this->updated_by,
            'updated_on' => $this->updated_on,
            'created_on' => $this->created_on,
            'tbl_purchase_payment.is_deleted' => 0,
        ]);

        $query->andFilterWhere(['like', 'purchase_payment_code', $this->purchase_payment_code])
            ->andFilterWhere(['like', 'purchase_payment_method', $this->purchase_payment_method])
            ->andFilterWhere(['like', 'tbl_purchase_order.purchase_order_code', $this->purchase_order_code]);

         if ($this->start_date != "" && preg_match('/^(\d{2})-(\d{2})-(\d{4})$/', $this->start_date))
            $query->andWhere('DATE(purchase_payment_date) >= "'.date("Y-m-d", strtotime($this->start_date)).'"');
        if ($this->end_date != "" && preg_match('/^(\d{2})-(\d{2})-(\d{4})$/', $this->end_date))
            $query->andWhere('DATE(purchase_payment_date) <= "'.date("Y-m-d", strtotime($this->end_date)).'"');

        return $dataProvider;
    }
}
