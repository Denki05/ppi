<?php

namespace frontend\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\IndentItem;

/**
 * IndentItemSearch represents the model behind the search form of `common\models\IndentItem`.
 */
class IndentItemSearch extends IndentItem
{
    public $indent_date, $product_name, $customer_name, $product_material_code, $product_material_name, $product_code, $factory_name, $brand_name, $category_name;
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'indent_id', 'product_id'], 'integer'],
            [['qty'], 'number'],
            [['indent_date', 'product_name', 'customer_name', 'product_code', 'product_material_name', 'product_material_code', 'factory_name', 'brand_name', 'category_name'], 'safe'],
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
        $query = IndentItem::find();
        $query->joinWith(array('indent', 'product', 'indent.customer as customer', 'product.factory as factory', 'product.brand as brand', 'product.category as category'));

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => (new \common\models\Setting)->getSettingValueByName('record_per_page')
            ],
            'sort'=>[
                'attributes' => [
                    'qty',
                    'customer_name' => [
                        'asc' => ['customer.customerName' => SORT_ASC],
                        'desc' => ['customer.customerName' => SORT_DESC],
                    ],
                    'indent_date' => [
                        'asc' => ['tbl_indent.indent_date' => SORT_ASC],
                        'desc' => ['tbl_indent.indent_date' => SORT_DESC],
                    ],
                    'product_name' => [
                        'asc' => ['tbl_product.product_name' => SORT_ASC],
                        'desc' => ['tbl_product.product_name' => SORT_DESC],
                    ],
                    'product_code' => [
                        'asc' => ['tbl_product.product_code' => SORT_ASC],
                        'desc' => ['tbl_product.product_code' => SORT_DESC],
                    ],
                    'product_material_name' => [
                        'asc' => ['tbl_product.product_material_name' => SORT_ASC],
                        'desc' => ['tbl_product.product_material_name' => SORT_DESC],
                    ],
                    'product_material_code' => [
                        'asc' => ['tbl_product.product_material_code' => SORT_ASC],
                        'desc' => ['tbl_product.product_material_code' => SORT_DESC],
                    ],
                    'factory_name' => [
                        'asc' => ['factory.factory_name' => SORT_ASC],
                        'desc' => ['factory.factory_name' => SORT_DESC],
                    ],
                    'brand_name' => [
                        'asc' => ['brand.brand_name' => SORT_ASC],
                        'desc' => ['brand.brand_name' => SORT_DESC],
                    ],
                    'category_name' => [
                        'asc' => ['category.category_name' => SORT_ASC],
                        'desc' => ['category.category_name' => SORT_DESC],
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
            'indent_id' => $this->indent_id,
            'product_id' => $this->product_id,
            'qty' => $this->qty,
            'indent_item_is_complete' => $this->indent_item_is_complete,
            'tbl_indent.is_deleted' => 0,
        ]);

        $query->andFilterWhere(['tbl_indent.indent_date' => $this->indent_date]);
        $query->andFilterWhere(['like', 'customer.customerName', $this->customer_name])
            ->andFilterWhere(['like', 'tbl_product.product_code', $this->product_code])
            ->andFilterWhere(['like', 'tbl_product.product_name', $this->product_name])
            ->andFilterWhere(['like', 'tbl_product.product_material_code', $this->product_material_code])
            ->andFilterWhere(['like', 'tbl_product.product_material_name', $this->product_material_name])
            ->andFilterWhere(['like', 'factory.factory_name', $this->factory_name])
            ->andFilterWhere(['like', 'brand.brand_name', $this->brand_name])
            ->andFilterWhere(['like', 'category.category_name', $this->category_name]);

        return $dataProvider;
    }
}
