<?php

namespace frontend\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Customer;

/**
 * CustomerSearch represents the model behind the search form of `common\models\Customer`.
 */
class CustomerSearch extends Customer
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'customer_has_tempo', 'customer_tempo_limit', 'customer_has_ppn', 'created_by', 'updated_by', 'is_deleted'], 'integer'],
            [['customer_store_code', 'customer_store_name', 'customer_zone', 'customer_province', 'customer_city', 'customer_type', 'customer_identity_card_number', 'customer_identity_card_image', 'customer_npwp', 'customer_npwp_image', 'customer_bank_name', 'customer_bank_acc_number', 'customer_bank_acc_name', 'customer_status', 'customer_owner_name', 'customer_birthday', 'customer_phone1', 'customer_phone2', 'created_on', 'updated_on', 'customer_store_address'], 'safe'],
            [['customer_credit_limit'], 'number'],
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
        $query = Customer::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
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
            'customer_has_tempo' => $this->customer_has_tempo,
            'customer_tempo_limit' => $this->customer_tempo_limit,
            'customer_credit_limit' => $this->customer_credit_limit,
            'customer_has_ppn' => $this->customer_has_ppn,
            'customer_birthday' => $this->customer_birthday,
            'created_on' => $this->created_on,
            'updated_on' => $this->updated_on,
            'created_by' => $this->created_by,
            'updated_by' => $this->updated_by,
            'is_deleted' => 0,
        ]);

        $query->andFilterWhere(['like', 'customer_store_code', $this->customer_store_code])
            ->andFilterWhere(['like', 'customer_store_name', $this->customer_store_name])
            ->andFilterWhere(['like', 'customer_zone', $this->customer_zone])
            ->andFilterWhere(['like', 'customer_province', $this->customer_province])
            ->andFilterWhere(['like', 'customer_city', $this->customer_city])
            ->andFilterWhere(['like', 'customer_type', $this->customer_type])
            ->andFilterWhere(['like', 'customer_identity_card_number', $this->customer_identity_card_number])
            ->andFilterWhere(['like', 'customer_identity_card_image', $this->customer_identity_card_image])
            ->andFilterWhere(['like', 'customer_npwp', $this->customer_npwp])
            ->andFilterWhere(['like', 'customer_store_address', $this->customer_store_address])
            ->andFilterWhere(['like', 'customer_npwp_image', $this->customer_npwp_image])
            ->andFilterWhere(['like', 'customer_bank_name', $this->customer_bank_name])
            ->andFilterWhere(['like', 'customer_bank_acc_number', $this->customer_bank_acc_number])
            ->andFilterWhere(['like', 'customer_bank_acc_name', $this->customer_bank_acc_name])
            ->andFilterWhere(['like', 'customer_status', $this->customer_status])
            ->andFilterWhere(['like', 'customer_owner_name', $this->customer_owner_name])
            // ->andFilterWhere(['like', 'customer_phone1', $this->customer_phone1])
            ->andFilterWhere(['like', 'customer_phone2', $this->customer_phone2]);

        $query->andFilterWhere(['or', ['like', 'customer_phone1', $this->customer_phone1], ['like', 'customer_phone2', $this->customer_phone1]]);

        return $dataProvider;
    }
}
