<?php

namespace frontend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Setting;

/**
 * SettingSearch represents the model behind the search form about `common\models\Setting`.
 */
class SettingSearch extends Setting
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['setting_id', 'setting_category_id'], 'integer'],
            [['setting_label', 'setting_name', 'setting_value', 'setting_desc', 'setting_input_type', 'setting_input_size'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
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
        $query = Setting::find();

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
            'setting_id' => $this->setting_id,
            'setting_category_id' => $this->setting_category_id,
        ]);

        $query->andFilterWhere(['like', 'setting_label', $this->setting_label])
            ->andFilterWhere(['like', 'setting_name', $this->setting_name])
            ->andFilterWhere(['like', 'setting_value', $this->setting_value])
            ->andFilterWhere(['like', 'setting_desc', $this->setting_desc])
            ->andFilterWhere(['like', 'setting_input_type', $this->setting_input_type])
            ->andFilterWhere(['like', 'setting_input_size', $this->setting_input_size]);

        return $dataProvider;
    }
}
