<?php

namespace frontend\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Product;

/**
 * ProductSearch represents the model behind the search form of `common\models\Product`.
 */
class ProductArchiveSearch extends Product
{
    public $factory_name, $brand_name, $category_name;
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'factory_id', 'brand_id', 'category_id', 'product_is_new', 'original_brand_id', 'searah_id', 'created_by', 'updated_by', 'is_deleted'], 'integer'],
            [['product_material_code', 'product_material_name', 'product_code', 'product_name', 'product_gender', 'product_web_image', 'product_status', 'created_on', 'updated_on', 'factory_name', 'brand_name', 'category_name', 'product_type'], 'safe'],
            [['product_cost_price', 'product_sell_price'], 'number'],
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
        $query = Product::find()->where(['product_status' => 'inactive']);
        $query->joinWith(array( 'factory', 'brand', 'category'));
        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => (new \common\models\Setting)->getSettingValueByName('record_per_page')
            ],
            'sort'=>[
                'attributes' => [
                    'product_material_name',
                    'product_material_code',
                    'product_name',
                    'product_code',
                    'product_status',
                    'factory_name' => [
                        'asc' => ['tbl_factory.factory_name' => SORT_ASC],
                        'desc' => ['tbl_factory.factory_name' => SORT_DESC],
                    ],
                    'brand_name' => [
                        'asc' => ['tbl_brand.brand_name' => SORT_ASC],
                        'desc' => ['tbl_brand.brand_name' => SORT_DESC],
                    ],
                    'category_name' => [
                        'asc' => ['tbl_category.category_name' => SORT_ASC],
                        'desc' => ['tbl_category.category_name' => SORT_DESC],
                    ],
                    'product_type',
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
            'factory_id' => $this->factory_id,
            'brand_id' => $this->brand_id,
            'category_id' => $this->category_id,
            'product_is_new' => $this->product_is_new,
            'original_brand_id' => $this->original_brand_id,
            'searah_id' => $this->searah_id,
            'product_cost_price' => $this->product_cost_price,
            'product_sell_price' => $this->product_sell_price,
            'created_on' => $this->created_on,
            'updated_on' => $this->updated_on,
            'created_by' => $this->created_by,
            'updated_by' => $this->updated_by,
            'tbl_product.is_deleted' => 0,
        ]);

        $query->andFilterWhere(['like', 'product_material_code', $this->product_material_code])
            ->andFilterWhere(['like', 'product_material_name', $this->product_material_name])
            ->andFilterWhere(['like', 'product_code', $this->product_code])
            ->andFilterWhere(['like', 'product_name', $this->product_name])
            ->andFilterWhere(['like', 'tbl_factory.factory_name', $this->factory_name])
            ->andFilterWhere(['like', 'tbl_brand.brand_name', $this->brand_name])
            ->andFilterWhere(['like', 'tbl_category.category_name', $this->category_name]);

        $query->andFilterWhere(['product_status' => $this->product_status]);
        $query->andFilterWhere(['product_type' => $this->product_type]);
            

        return $dataProvider;
    }
}
