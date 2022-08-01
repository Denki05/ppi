<?php

namespace frontend\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\ComissionPay;

/**
 * ComissionPaySearch represents the model behind the search form of `common\models\ComissionPay`.
 */
class ComissionPaySearch extends ComissionPay
{
    public $salesman_name, $start_date, $end_date;
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'salesman_id', 'created_by', 'updated_by', 'is_deleted'], 'integer'],
            [['comission_pay_date', 'comission_pay_periode', 'created_on', 'updated_on', 'salesman_name', 'start_date', 'end_date'], 'safe'],
            [['comission_pay_exchange_rate', 'comission_pay_value'], 'number'],
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
        $query = ComissionPay::find();
        $query->joinWith(array('salesman'));

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => (new \common\models\Setting)->getSettingValueByName('record_per_page')
            ],
            'sort'=>[
                'attributes' => [
                    'comission_pay_date',
                    'comission_pay_periode',
                    'comission_pay_value',
                    'salesman_name' => [
                        'asc' => ['tbl_employee.employee_name' => SORT_ASC],
                        'desc' => ['tbl_employee.employee_name' => SORT_DESC],
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
            'salesman_id' => $this->salesman_id,
            'comission_pay_date' => $this->comission_pay_date,
            'comission_pay_exchange_rate' => $this->comission_pay_exchange_rate,
            'comission_pay_value' => $this->comission_pay_value,
            'created_by' => $this->created_by,
            'updated_by' => $this->updated_by,
            'created_on' => $this->created_on,
            'updated_on' => $this->updated_on,
            'is_deleted' => 0,
        ]);

        $query->andFilterWhere(['like', 'tbl_employee.employee_name', $this->salesman_name]);
        $query->andFilterWhere(['comission_pay_periode' =>  $this->comission_pay_periode]);

        if ($this->start_date != "" && preg_match('/^(\d{2})-(\d{2})-(\d{4})$/', $this->start_date))
            $query->andWhere('DATE(comission_pay_date) >= "'.date("Y-m-d", strtotime($this->start_date)).'"');
        if ($this->end_date != "" && preg_match('/^(\d{2})-(\d{2})-(\d{4})$/', $this->end_date))
            $query->andWhere('DATE(comission_pay_date) <= "'.date("Y-m-d", strtotime($this->end_date)).'"');

        return $dataProvider;
    }
}
