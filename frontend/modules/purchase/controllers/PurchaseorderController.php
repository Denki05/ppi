<?php

namespace frontend\modules\purchase\controllers;

use Yii;
use common\models\SalesInvoice;
use common\models\SalesInvoiceItem;
use common\models\PurchaseOrder;
use common\models\PurchaseOrderItem;
use common\models\Category;
use common\models\Product;
use common\models\Customer;
use frontend\models\SalesInvoiceSearch;
use frontend\models\PurchaseOrderSearch;
// use yii\web\Controller;
use common\components\ErrorGenerateComponent;
use frontend\components\LabelComponent;
use app\components\BaseController;
use yii\web\NotFoundHttpException;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use yii\helpers\Json;

/**
 * DefaultController implements the CRUD actions for DiscMaster model.
 */
class PurchaseorderController extends BaseController
{
   
    public function actionIndex()
    {
        BaseController::$page_caption = 'Purchase Order';
        
        $searchModel = new PurchaseOrderSearch();
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
            'product_name' => $item->product_name,
            'id' => $item->id,
            'product_sell_price' => $item->product_sell_price,
        );
        
        return Json::encode($hasil);
        die();
    }

    public function actionGetitemcustomer($id)
    {
        $item = Customer::find()->where('id=:id',[':id'=>$id])->one();

        $hasil = array();
        if(!empty($item)){
            $hasil = array(
                'customer_debt_amount' => $item->getCustomerDebtAmount(),
                'customer_credit_limit' => $item->customer_credit_limit,
            );
        }
        
        return Json::encode($hasil);
        die();
    }
    
    public function actionView($id)
    {
        BaseController::$page_caption = 'Lihat Purchase Order';
        
        $model = $this->findModel($id);

        return $this->render('view', [
            'model' => $model
        ]);
    }

    public function actionCreate()
    {
        BaseController::$page_caption = 'Tambah Purchase Order';

        $model = new PurchaseOrder();
        $model->purchase_order_date = date('d-m-Y');

        $items = array();

        if ($model->load(Yii::$app->request->post())) {
            $noProblem = true;

            $items = $_POST['item'];
            
            $trans = Yii::$app->db->beginTransaction();

            $model->purchase_order_date = !empty($model->purchase_order_date) ? date("Y-m-d", strtotime($model->purchase_order_date)) : NULL;    
            
            $model->purchase_order_code = $model->getLatestNumber('PO', 'purchase_order_code');
            $model->supplier_id = 1;

            if($model->save()){
                $noItem = true;
                foreach($items as $i => $item) {
                    if ($i !== "{index}") {
                        $noItem = false;
                        $temp = new PurchaseOrderItem;
                        $temp->purchase_order_id = $model->id;
                        $temp->product_id = $item['product_id'];
                        $temp->purchase_order_item_qty = $item['purchase_order_item_qty'];
                        $temp->purchase_order_item_disc_amount = $item['purchase_order_item_disc_amount'];
                        $temp->purchase_order_item_disc_percent = $item['purchase_order_item_disc_percent'];
                        $temp->purchase_order_item_price = $item['purchase_order_item_price'];
                        $temp->purchase_order_item_row_total = $item['purchase_order_item_row_total'];
                        if (!$temp->save()) {
                            $errorMessage = ErrorGenerateComponent::generateErrorLabels($temp->getErrors());
                            $noProblem = false;
                            Yii::$app->session->setFlash('error', $errorMessage);
                        }
                    }
                }

                if ($noItem) {
                    Yii::$app->session->setFlash('danger', 'Paling sedikit harus memilih 1 barang');
                    $noProblem = false;
                }
            }
            else{
                $errorMessage = ErrorGenerateComponent::generateErrorLabels($model->getErrors());
                $noProblem = false;
                Yii::$app->session->setFlash('danger', $errorMessage);
            }

            if ($noProblem) {
                $trans->commit();
                Yii::$app->session->setFlash('success', 'Nota '.LabelComponent::SUCCESS_SAVE);
                return $this->redirect(['index']);
            }

            $trans->rollback();
			$model->purchase_order_date = !empty($model->purchase_order_date) ? date("d-m-Y", strtotime($model->purchase_order_date)) : NULL;    
            
        }

        return $this->render('form', [
            'model' => $model,
            'mode' => 'create',
            'items' => $items,
        ]);
    }

    public function actionUpdate($id)
    {
		BaseController::$page_caption = 'Update Purchase Order';
		
		$model = $this->findModel($id);
        $model->purchase_order_date = !empty($model->purchase_order_date) ? date("d-m-Y", strtotime($model->purchase_order_date)) : NULL;
        
        $items = array();

        foreach($model->purchaseOrderItems as $item) {
            $items[] = [
                'product_id' => $item->product_id,
                'product_name' => $item->product->product_name,
                'purchase_order_item_disc_amount' => $item->purchase_order_item_disc_amount,
                'purchase_order_item_disc_percent' => $item->purchase_order_item_disc_percent,
                'purchase_order_item_price' => $item->purchase_order_item_price,
                'purchase_order_item_qty' => $item->purchase_order_item_qty,
                'purchase_order_item_row_total' => $item->purchase_order_item_row_total,
            ];
        }

        if ($model->load(Yii::$app->request->post())) {
            $noProblem = true;

            $items = $_POST['item'];
            
            $trans = Yii::$app->db->beginTransaction();

            $model->purchase_order_date = !empty($model->purchase_order_date) ? date("Y-m-d", strtotime($model->purchase_order_date)) : NULL;    
            $model->supplier_id = 1;
            
            if($model->save()){
                $noItem = true;
                PurchaseOrderItem::deleteAll('purchase_order_id=:id', [':id' => $id]);
                foreach($items as $i => $item) {
                    if ($i !== "{index}") {
                        $noItem = false;
                        $temp = new PurchaseOrderItem;
                        $temp->purchase_order_id = $model->id;
                        $temp->product_id = $item['product_id'];
                        $temp->purchase_order_item_qty = $item['purchase_order_item_qty'];
                        $temp->purchase_order_item_disc_amount = $item['purchase_order_item_disc_amount'];
                        $temp->purchase_order_item_disc_percent = $item['purchase_order_item_disc_percent'];
                        $temp->purchase_order_item_price = $item['purchase_order_item_price'];
                        $temp->purchase_order_item_row_total = $item['purchase_order_item_row_total'];
                        if (!$temp->save()) {
                            $errorMessage = ErrorGenerateComponent::generateErrorLabels($temp->getErrors());
                            $noProblem = false;
                            Yii::$app->session->setFlash('error', $errorMessage);
                        }
                    }
                }

                if ($noItem) {
                    Yii::$app->session->setFlash('danger', 'Paling sedikit harus memilih 1 barang');
                    $noProblem = false;
                }
            }
            else{
                $errorMessage = ErrorGenerateComponent::generateErrorLabels($model->getErrors());
                $noProblem = false;
                Yii::$app->session->setFlash('danger', $errorMessage);
            }

            if ($noProblem) {
                $trans->commit();
                Yii::$app->session->setFlash('success', 'Nota '.LabelComponent::SUCCESS_SAVE);
                return $this->redirect(['index']);
            }

            $trans->rollback();
            $model->purchase_order_date = !empty($model->purchase_order_date) ? date("d-m-Y", strtotime($model->purchase_order_date)) : NULL;    
        }

        return $this->render('form', [
            'model' => $model,
            'mode' => 'update',
            'items' => $items,
        ]);
    }

    public function actionDelete($id)
    {
        $model  = $this->findModel($id);
        $model->is_deleted = 1;
        $model->save();

        return $this->redirect(['index']);
    }

  //   /**
  //    * Finds the DiscMaster model based on its primary key value.
  //    * If the model is not found, a 404 HTTP exception will be thrown.
  //    * @param integer $id
  //    * @return DiscMaster the loaded model
  //    * @throws NotFoundHttpException if the model cannot be found
  //    */
    protected function findModel($id)
    {
        if (($model = PurchaseOrder::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('Purchase Order '.LabelComponent::NOT_FOUND);
    }
}
