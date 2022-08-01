<?php

namespace frontend\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\PurchaseOrder;

/**
 * PurchaseOrderSearch represents the model behind the search form of `common\models\PurchaseOrder`.
 */
class PurchaseOrderSearch extends PurchaseOrder
{
    public $supplier_name, $start_date, $end_date;
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'supplier_id', 'created_by', 'updated_by', 'is_deleted'], 'integer'],
            [['purchase_order_code', 'purchase_order_date', 'purchase_order_status', 'purchase_order_notes', 'created_on', 'updated_on', 'supplier_name', 'start_date', 'end_date'], 'safe'],
            [['purchase_order_subtotal', 'purchase_order_disc_percent', 'purchase_order_disc_amount', 'purchase_order_grand_total'], 'number'],
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
        $query = PurchaseOrder::find();
        $query->joinWith(array('supplier'));

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => (new \common\models\Setting)->getSettingValueByName('record_per_page')
            ],
            'sort'=>[
                'attributes' => [
                    'purchase_order_code',
                    'purchase_order_date',
                    'purchase_order_status',
                    'purchase_order_grand_total',
                    'purchase_order_notes',
                    'supplier_name' => [
                        'asc' => ['tbl_supplier.supplier_name' => SORT_ASC],
                        'desc' => ['tbl_supplier.supplier_name' => SORT_DESC],
                    ],
                ],
                // 'defaultOrder' => ['invoice_code' => SORT_DESC],
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
            'supplier_id' => $this->supplier_id,
            // 'purchase_order_date' => $this->purchase_order_date,
            'purchase_order_subtotal' => $this->purchase_order_subtotal,
            'purchase_order_disc_percent' => $this->purchase_order_disc_percent,
            'purchase_order_disc_amount' => $this->purchase_order_disc_amount,
            'purchase_order_grand_total' => $this->purchase_order_grand_total,
            'created_on' => $this->created_on,
            'updated_on' => $this->updated_on,
            'created_by' => $this->created_by,
            'updated_by' => $this->updated_by,
            'tbl_purchase_order.is_deleted' => 0,
        ]);

        $query->andFilterWhere(['like', 'purchase_order_code', $this->purchase_order_code])
            ->andFilterWhere(['like', 'purchase_order_status', $this->purchase_order_status])
            ->andFilterWhere(['like', 'tbl_supplier.supplier_name', $this->supplier_name])
            ->andFilterWhere(['like', 'purchase_order_notes', $this->purchase_order_notes]);

        if ($this->start_date != "" && preg_match('/^(\d{2})-(\d{2})-(\d{4})$/', $this->start_date))
            $query->andWhere('DATE(purchase_order_date) >= "'.date("Y-m-d", strtotime($this->start_date)).'"');
        if ($this->end_date != "" && preg_match('/^(\d{2})-(\d{2})-(\d{4})$/', $this->end_date))
            $query->andWhere('DATE(purchase_order_date) <= "'.date("Y-m-d", strtotime($this->end_date)).'"');


        return $dataProvider;
    }
}
