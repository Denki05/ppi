<?php

namespace frontend\modules\sales\controllers;

use Yii;
use common\models\Indent;
use common\models\IndentItem;
use common\models\Customer;
use common\models\Product;
use common\models\Category;
use common\components\ErrorGenerateComponent;
use frontend\models\IndentItemSearch;
use frontend\components\LabelComponent;
use frontend\models\CustomerSearch;
use frontend\models\OrderSearch;
use app\components\BaseController;
use yii\web\NotFoundHttpException;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use kartik\mpdf\Pdf;
use yii\helpers\Json;

/**
 * IndentController implements the CRUD actions for DiscMaster model.
 */
class IndentController extends BaseController
{
   
    public function actionIndex()
    {
        BaseController::$page_caption = 'Daftar Indent';
        
        $searchModel = new IndentItemSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionGetitemcategory($id)
    {
        $items = Category::find()->where('brand_id=:id',[':id'=>$id])->andWhere('is_deleted=:is', [':is' => 0])->orderBy(['category_name' => SORT_ASC])->all();

        $hasil = array();
        foreach ($items as $item) {
            $hasil[] = [
                'id' => $item->id,
                'category_name' => $item->category_name,
            ];
        }
        
        
        return Json::encode($hasil);
        die();
    }

    public function actionGetitemproduct($brandId, $categoryId)
    {
        $items = Product::find()->where('brand_id=:id',[':id'=>$brandId])->andWhere('category_id=:idc', [':idc' => $categoryId])->andWhere('is_deleted=:is', [':is' => 0])->orderBy(['product_name' => SORT_ASC])->all();

        $hasil = array();
        foreach ($items as $item) {
            $hasil[] = [
                'id' => $item->id,
                'product_name' => $item->product_name,
            ];
        }
        
        
        return Json::encode($hasil);
        die();
    }

    public function actionGetitemrow($id)
    {
        $item = Product::find()->where('id=:id',[':id'=>$id])->one();

        $hasil = array(
        	'brand_name' => $item->brand->brand_name,
        	'category_name' => $item->category->category_name,
        	'product_name' => $item->product_name,
        	'id' => $item->id,
        );
        
        
        
        return Json::encode($hasil);
        die();
    }
    
    public function actionCreate()
    {
        BaseController::$page_caption = 'Tambah Indent';

        $model = new Indent();
        $model->indent_date = date("d-m-Y");

		$items = array();

        if ($model->load(Yii::$app->request->post())) {
			$noProblem = true;

			$items = $_POST['item'];
			
			$trans = Yii::$app->db->beginTransaction();
			
			$model->indent_date = !empty($model->indent_date) ? date("Y-m-d", strtotime($model->indent_date)) : NULL;
			
			if($model->save()){
				foreach($items as $i => $item) {
					if ($i !== "{index}") {
						$temp = new IndentItem;
						$temp->indent_id = $model->id;
						$temp->qty = $item['indent_item_qty'];
						$temp->product_id = $item['product_id'];
						// $temp->indent_item_is_complete = 0;
						if (!$temp->save()) {
							$errorMessage = ErrorGenerateComponent::generateErrorLabels($temp->getErrors());
							$noProblem = false;
							Yii::$app->session->setFlash('error', $errorMessage);
						}
					}
				}
			}
			else{
				$errorMessage = ErrorGenerateComponent::generateErrorLabels($model->getErrors());
				$noProblem = false;
				Yii::$app->session->setFlash('error', $errorMessage);
			}

			
			if ($noProblem) {
				$trans->commit();
				Yii::$app->session->setFlash('success', 'Indent '.LabelComponent::SUCCESS_SAVE);
				return $this->redirect(['index']);
			}
			
			$trans->rollback();
			
			$model->indent_date = !empty($model->indent_date) ? date("d-m-Y", strtotime($model->indent_date)) : NULL;	
        }

        return $this->render('form', [
            'model' => $model,
            'mode' => 'create',
            'items' => $items,
        ]);
    }

    public function actionUpdate($id)
    {
		BaseController::$page_caption = 'Update Indent';
		
		$model = $this->findModel($id);
		$model->indent_date = date("d-m-Y", strtotime($model->indent_date));

		$items = array();
		foreach($model->indentItems as $item) {
			$items[] = [
				'product_id' => $item->product_id,
				'product_name' => $item->product->product_name,
				'brand_name' => $item->product->brand->brand_name,
				'category_name' => $item->product->category->category_name,
				'indent_item_qty' => $item->qty,
			];
		}

        if ($model->load(Yii::$app->request->post())) {
			$noProblem = true;

			$items = $_POST['item'];

			$trans = Yii::$app->db->beginTransaction();
			
			$model->indent_date = !empty($model->indent_date) ? date("Y-m-d", strtotime($model->indent_date)) : NULL;
			
			if($model->save()){
				
				IndentItem::deleteAll('indent_id=:id', [':id' => $id]);
				foreach($items as $i => $item) {
					if ($i !== "{index}") {
						$temp = new IndentItem;
						$temp->indent_id = $model->id;
						$temp->qty = $item['indent_item_qty'];
						$temp->product_id = $item['product_id'];
						// $temp->indent_item_is_complete = 0;
						if (!$temp->save()) {
							$errorMessage = ErrorGenerateComponent::generateErrorLabels($temp->getErrors());
							$noProblem = false;
							Yii::$app->session->setFlash('error', $errorMessage);
						}
					}
				}
			}
			else{
				$errorMessage = ErrorGenerateComponent::generateErrorLabels($model->getErrors());
				$noProblem = false;
				Yii::$app->session->setFlash('error', $errorMessage);
			}

			
			if ($noProblem) {
				$trans->commit();
				Yii::$app->session->setFlash('success', 'Indent '.LabelComponent::SUCCESS_SAVE);
				return $this->redirect(['index']);
			}
			
			$trans->rollback();
			
			$model->indent_date = !empty($model->indent_date) ? date("d-m-Y", strtotime($model->indent_date)) : NULL;	
        }

        return $this->render('form', [
            'model' => $model,
            'mode' => 'update',
            'items' => $items
        ]);
    }

    public function actionUpdateiscompletetrue($id)
    {
        $model = IndentItem::find()->where('id=:id', [':id' => $id])->one();
        $model->indent_item_is_complete = 1;
        $model->save();
    }

    public function actionUpdateiscompletefalse($id)
    {
        $model = IndentItem::find()->where('id=:id', [':id' => $id])->one();
        $model->indent_item_is_complete = 0;
        $model->save();
    }

    public function actionDelete($id)
    {
        $model  = IndentItem::find()->where('id=:id', [':id' => $id])->one();
        $model->delete();

        return $this->redirect(['index']);
    }
	
    protected function findModel($id)
    {
        if (($model = Indent::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('Pemesanan '.LabelComponent::NOT_FOUND);
    }
}
