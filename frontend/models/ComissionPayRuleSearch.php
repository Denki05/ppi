<?php

namespace frontend\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\ComissionPayRule;

/**
 * ComissionPayRuleSearch represents the model behind the search form of `common\models\ComissionPayRule`.
 */
class ComissionPayRuleSearch extends ComissionPayRule
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'comission_pay_rule_start_day', 'comission_pay_rule_end_day', 'created_by', 'updated_by', 'is_deleted'], 'integer'],
            [['comission_pay_rule_value'], 'number'],
            [['created_on', 'updated_on'], 'safe'],
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
        $query = ComissionPayRule::find();

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
            'comission_pay_rule_start_day' => $this->comission_pay_rule_start_day,
            'comission_pay_rule_end_day' => $this->comission_pay_rule_end_day,
            'comission_pay_rule_value' => $this->comission_pay_rule_value,
            'created_by' => $this->created_by,
            'updated_by' => $this->updated_by,
            'created_on' => $this->created_on,
            'updated_on' => $this->updated_on,
            'is_deleted' => 0,
        ]);

        return $dataProvider;
    }
}
