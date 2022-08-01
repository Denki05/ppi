<?php

namespace frontend\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\ComissionType;

/**
 * ComissionTypeSearch represents the model behind the search form of `common\models\ComissionType`.
 */
class ComissionTypeSearch extends ComissionType
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'created_by', 'updated_by', 'is_deleted'], 'integer'],
            [['comission_type_name', 'comission_type_desc', 'created_on', 'updated_on'], 'safe'],
            [['comission_type_value'], 'number'],
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
        $query = ComissionType::find();

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
            'comission_type_value' => $this->comission_type_value,
            'created_on' => $this->created_on,
            'updated_on' => $this->updated_on,
            'created_by' => $this->created_by,
            'updated_by' => $this->updated_by,
            'is_deleted' => 0,
        ]);

        $query->andFilterWhere(['like', 'comission_type_name', $this->comission_type_name])
            ->andFilterWhere(['like', 'comission_type_desc', $this->comission_type_desc]);

        return $dataProvider;
    }
}
