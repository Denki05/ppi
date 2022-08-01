<?php

namespace frontend\modules\purchase\controllers;

use Yii;
use common\models\SalesInvoice;
use common\models\PurchaseOrder;
use common\models\PurchaseOrderItem;
use common\models\SalesInvoiceItem;
use common\models\SalesPayment;
use common\models\PurchasePayment;
use common\models\Bank;
use common\models\SalesPaymentDetail;
use common\models\PurchasePaymentDetail;
use frontend\models\SalesInvoiceSearch;
use frontend\models\SalesPaymentSearch;
use frontend\models\PurchasePaymentSearch;
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
class PurchasepaymentController extends BaseController
{
   
    public function actionIndex()
    {
        BaseController::$page_caption = 'Pembayaran PO';
        
        $searchModel = new PurchasePaymentSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionGetitem($id)
    {
        $items = Bank::find()
        // ->select('product_id','quotation_item_price','quotation_item_id')
        ->where('id=:id',[':id'=>$id])->one();

        $hasil = array('bank_acc_name' => $items->bank_acc_name,
                'bank_acc_number' => $items->bank_acc_number);
        
        return Json::encode($hasil);
        die();
    }

    public function actionGetdetailpurchase($id)
    {
        $items = PurchaseOrder::find()->where('id=:id',[':id'=>$id])->andWhere('is_deleted=:is', [':is' => 0])->one();

        $hasil = array(
            'id' => $items->id,
            'purchase_order_code' => $items->purchase_order_code,
            'purchase_order_date' => date("d-m-Y", strtotime($items->purchase_order_date)),
            'supplier_name' => $items->supplier->supplier_name,
            'purchase_order_status' =>$items->purchase_order_status,
            'purchase_order_disc_percent' =>$items->purchase_order_disc_percent,
            'purchase_order_disc_amount' =>$items->purchase_order_disc_amount,
            'purchase_order_subtotal' => $items->purchase_order_subtotal,
            'purchase_order_grand_total' => $items->purchase_order_grand_total,
            'purchase_order_notes' =>$items->purchase_order_notes,
            'paid_amount' => (new PurchasePayment)->getPaidAmount($items->id),
            );
        
        return Json::encode($hasil);
        die();
    }

    public function actionGetitempurchase($id)
    {
        $items = PurchaseOrderItem::find()->where('purchase_order_id=:id', [':id' => $id])->all();
        $hasil = array();
        foreach($items as $item) {
            $hasil[] = [
                'purchase_product_code' => $item->product->product_code,
                'purchase_product_name' => $item->product->product_name,
                'purchase_order_item_qty' => $item->purchase_order_item_qty,
                'purchase_order_item_price' => $item->purchase_order_item_price,
                'purchase_order_item_disc_percent' => $item->purchase_order_item_disc_percent,
                'purchase_order_item_disc_amount' => $item->purchase_order_item_disc_amount,
                'purchase_order_item_row_total' => $item->purchase_order_item_row_total,
            ];
        }
        
        return Json::encode($hasil);
        die();
    }

    public function actionView($id)
    {
        BaseController::$page_caption = 'Lihat Pembayaran PO';
        
        $model = $this->findModel($id);

        return $this->render('view', [
            'model' => $model
        ]);
    }

    public function actionCreate()
    {
        BaseController::$page_caption = 'Tambah Pembayaran PO';

        $model = new PurchasePayment();
        $model->purchase_payment_date = date('d-m-Y');

        $items = array();

        if ($model->load(Yii::$app->request->post())) {
            $noProblem = true;

            $items = $_POST['item'];
            
            $trans = Yii::$app->db->beginTransaction();

            $model->purchase_payment_date = !empty($model->purchase_payment_date) ? date("Y-m-d", strtotime($model->purchase_payment_date)) : NULL;    
            
            $model->purchase_payment_code = $model->getLatestNumber('PP', 'purchase_payment_code');
            $model->supplier_id = 1;

            if($model->save()){
                $noItem = true;
                foreach($items as $i => $item) {
                    if ($i !== "{index}") {
                        $noItem = false;
                        $temp = new PurchasePaymentDetail;
                        $temp->purchase_payment_id = $model->id;
                        $temp->purchase_payment_detail_amount = $item['purchase_payment_detail_amount'];
                        $temp->purchase_payment_detail_method = $item['purchase_payment_detail_method'];
                        $temp->bank_id = $item['bank_id'];
                        $temp->purchase_payment_detail_bank_acc_number = $item['purchase_payment_detail_bank_acc_number'];
                        $temp->purchase_payment_detail_bank_acc_name = $item['purchase_payment_detail_bank_acc_name'];
                        $temp->purchase_payment_detail_debitcard_number = $item['purchase_payment_detail_debitcard_number'];
                        $temp->purchase_payment_detail_creditcard_number = $item['purchase_payment_detail_creditcard_number'];
                        if (!$temp->save()) {
                            $errorMessage = ErrorGenerateComponent::generateErrorLabels($temp->getErrors());
                            $noProblem = false;
                            Yii::$app->session->setFlash('danger', $errorMessage);
                        }
                    }
                }

                if ($noItem) {
                    Yii::$app->session->setFlash('danger', 'Tidak ada pembayaran');
                    $noProblem = false;
                }

                $purchase = PurchaseOrder::find()->andWhere('id=:id', [':id' => $model->purchase_order_id])->one();
                
                if ($purchase->purchase_order_grand_total < $model->purchase_payment_total_amount) {
                    Yii::$app->session->setFlash('danger', 'Jumlah bayar tidak boleh lebih dari total PO pembayaran');
                    $noProblem = false;
                }
                else {
                    $purchase->updateStatus($model->purchase_order_id);
                }

            }
            else{
                $errorMessage = ErrorGenerateComponent::generateErrorLabels($model->getErrors());
                $noProblem = false;
                Yii::$app->session->setFlash('danger', $errorMessage);
            }

            if ($noProblem) {
                $trans->commit();
                Yii::$app->session->setFlash('success', 'Pembayaran PO '.LabelComponent::SUCCESS_SAVE);
                return $this->redirect(['index']);
            }

            $trans->rollback();
			$model->purchase_payment_date = !empty($model->purchase_payment_date) ? date("d-m-Y", strtotime($model->purchase_payment_date)) : NULL;
        }

        return $this->render('form', [
            'model' => $model,
            'mode' => 'create',
            'items' => $items,
        ]);
    }

    public function actionUpdate($id)
    {
        BaseController::$page_caption = 'Update Pembayaran PO';
        $model = $this->findModel($id);
        $model->purchase_payment_date = !empty($model->purchase_payment_date) ? date("d-m-Y", strtotime($model->purchase_payment_date)) : NULL;    
        

        $items = array();
        foreach($model->purchasePaymentDetails as $item) {
            $items[] = [
                'purchase_payment_id' => $item->purchase_payment_id,
                'purchase_payment_detail_method' => $item->purchase_payment_detail_method,
                'purchase_payment_detail_amount' => $item->purchase_payment_detail_amount,
                'bank_id' => $item->bank_id,
                'purchase_payment_detail_bank_acc_name' => $item->purchase_payment_detail_bank_acc_name,
                'purchase_payment_detail_bank_acc_number' => $item->purchase_payment_detail_bank_acc_number,
                'purchase_payment_detail_creditcard_number' => $item->purchase_payment_detail_creditcard_number,
                'purchase_payment_detail_debitcard_number' => $item->purchase_payment_detail_debitcard_number,
            ];
        }

        if ($model->load(Yii::$app->request->post())) {
            $noProblem = true;

            $items = $_POST['item'];
            
            $trans = Yii::$app->db->beginTransaction();

            $model->purchase_payment_date = !empty($model->purchase_payment_date) ? date("Y-m-d", strtotime($model->purchase_payment_date)) : NULL;    
            
            $model->supplier_id = 1;

            if($model->save()){
                $noItem = true;

                PurchasePaymentDetail::deleteAll('purchase_payment_id=:id', [':id' => $id]);

                foreach($items as $i => $item) {
                    if ($i !== "{index}") {
                        $noItem = false;
                        $temp = new PurchasePaymentDetail;
                        $temp->purchase_payment_id = $model->id;
                        $temp->purchase_payment_detail_amount = $item['purchase_payment_detail_amount'];
                        $temp->purchase_payment_detail_method = $item['purchase_payment_detail_method'];
                        $temp->bank_id = $item['bank_id'];
                        $temp->purchase_payment_detail_bank_acc_number = $item['purchase_payment_detail_bank_acc_number'];
                        $temp->purchase_payment_detail_bank_acc_name = $item['purchase_payment_detail_bank_acc_name'];
                        $temp->purchase_payment_detail_debitcard_number = $item['purchase_payment_detail_debitcard_number'];
                        $temp->purchase_payment_detail_creditcard_number = $item['purchase_payment_detail_creditcard_number'];
                        if (!$temp->save()) {
                            $errorMessage = ErrorGenerateComponent::generateErrorLabels($temp->getErrors());
                            $noProblem = false;
                            Yii::$app->session->setFlash('danger', $errorMessage);
                        }
                    }
                }

                if ($noItem) {
                    Yii::$app->session->setFlash('danger', 'Tidak ada pembayaran');
                    $noProblem = false;
                }

                $purchase = PurchaseOrder::find()->andWhere('id=:id', [':id' => $model->purchase_order_id])->one();
                
                if ($purchase->purchase_order_grand_total < $model->purchase_payment_total_amount) {
                    Yii::$app->session->setFlash('danger', 'Jumlah bayar tidak boleh lebih dari total PO pembayaran');
                    $noProblem = false;
                }
                else {
                    $purchase->updateStatus($model->purchase_order_id);
                }

            }
            else{
                $errorMessage = ErrorGenerateComponent::generateErrorLabels($model->getErrors());
                $noProblem = false;
                Yii::$app->session->setFlash('danger', $errorMessage);
            }

            if ($noProblem) {
                $trans->commit();
                Yii::$app->session->setFlash('success', 'Pembayaran PO'.LabelComponent::SUCCESS_SAVE);
                return $this->redirect(['index']);
            }

            $trans->rollback();
            $model->purchase_payment_date = !empty($model->purchase_payment_date) ? date("d-m-Y", strtotime($model->purchase_payment_date)) : NULL;
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

        if ($model->save()){
            Yii::$app->session->setFlash('success', 'Pembayaran PO '.LabelComponent::SUCCESS_DELETE);
            $po = PurchaseOrder::find()->andWhere('id=:id', [':id' => $model->purchase_order_id])->one();
            $po->updateStatus($model->purchase_order_id);
        }
        else {
            $errorString = ErrorGenerateComponent::generateErrorLabels($model->getErrors());
            Yii::$app->session->setFlash('danger', 'Pembayaran PO '.LabelComponent::ERROR_DELETE.'.'.$errorString."Silakan update dulu dan coba hapus lagi");
        }

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
        if (($model = PurchasePayment::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('Pembayaran PO '.LabelComponent::NOT_FOUND);
    }
}
