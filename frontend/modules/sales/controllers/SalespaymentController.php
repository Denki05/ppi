<?php

namespace frontend\modules\sales\controllers;

use Yii;
use common\models\SalesInvoice;
use common\models\SalesInvoiceItem;
use common\models\SalesPayment;
use common\models\Bank;
use common\models\SalesPaymentDetail;
use frontend\models\SalesInvoiceSearch;
use frontend\models\SalesPaymentSearch;
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
class SalespaymentController extends BaseController
{
   
    public function actionIndex()
    {
        BaseController::$page_caption = 'Pembayaran';
        
        $searchModel = new SalesPaymentSearch();
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
    
    public function actionGetinvoice($id)
    {
        $items = SalesInvoice::find()->where('customer_id=:id',[':id'=>$id])->andWhere('invoice_payment_status != :p', [':p' => SalesInvoice::STATUS_PAYMENT_PAID])
        ->andWhere('is_deleted=:is', [':is' => 0])->orderBy(['invoice_code' => SORT_ASC])->all();

        $hasil = array();
        foreach ($items as $item) {
            $hasil[] = [
                'id' => $item->id,
                'invoice_code' => $item->invoice_code,
            ];
        }
        
        
        return Json::encode($hasil);
        die();
    }

    public function actionGetdetailinvoice($id)
    {
        $items = SalesInvoice::find()->where('id=:id',[':id'=>$id])->andWhere('is_deleted=:is', [':is' => 0])->orderBy(['invoice_code' => SORT_ASC])->one();

        $hasil = array(
            'id' => $items->id,
            'invoice_code' => $items->invoice_code,
            'invoice_date' => $items->invoice_date,
            'customer_name' => $items->customer->customerName,
            'salesman_name' =>$items->salesman->employee_name,
            'comission_type_name' =>isset($items->comissionType->comission_type_name) ? $items->comissionType->comission_type_name : '',
            'invoice_disc_amount' =>$items->invoice_disc_amount,
            'invoice_disc_percent' =>$items->invoice_disc_percent,
            'invoice_disc_amount2' =>$items->invoice_disc_amount2,
            'invoice_disc_percent2' =>$items->invoice_disc_percent2,
            'invoice_shipping_cost' =>$items->invoice_shipping_cost,
            'invoice_tax_amount' =>$items->invoice_tax_amount,
            'invoice_tax_percent' =>isset($items->invoice_tax_percent) ? $items->invoice_tax_percent : 0,
            'invoice_subtotal' => $items->invoice_subtotal,
            'invoice_grand_total' => $items->invoice_grand_total,
            'invoice_outstanding_amount' => $items->invoice_outstanding_amount,
            'invoice_exchange_rate' =>$items->invoice_exchange_rate,
            'invoice_comission_pay_date' => $items->invoice_comission_pay_date,
            'invoice_comission_value' => $items->invoice_comission_value,
            // 'customer_debt_amount' => (new SalesPayment)->getInvoiceDebtAmount($items->id, $items->customer_id),
            'customer_paid_amount' => (new SalesPayment)->getInvoicePaidAmount($items->id, $items->customer_id),
            );
        
        return Json::encode($hasil);
        die();
    }

    public function actionGetiteminvoice($id)
    {
        $items = SalesInvoiceItem::find()->where('invoice_id=:id', [':id' => $id])->all();
        $hasil = array();
        foreach($items as $item) {
            $currency = '$';
            if($item->invoice->invoice_exchange_rate > 1)
                $currency = 'Rp';
            $hasil[] = [
                'invoice_product_code' => $item->product->product_code,
                'invoice_product_name' => $item->product->product_name,
                'invoice_item_qty' => $item->invoice_item_qty,
                'invoice_item_price' => $item->invoice_item_price,
                'invoice_item_disc_percent' => $item->invoice_item_disc_percent,
                'invoice_item_disc_amount' => $item->invoice_item_disc_amount,
                'invoice_item_row_total' => $currency.''.$item->invoice_item_row_total,
            ];
        }
        
        return Json::encode($hasil);
        die();
    }

    public function actionView($id)
    {
        BaseController::$page_caption = 'Lihat Pembayaran';
        
        $model = $this->findModel($id);

        return $this->render('view', [
            'model' => $model
        ]);
    }

    public function actionCreate()
    {
        BaseController::$page_caption = 'Tambah Pembayaran';

        $model = new SalesPayment();
        $model->payment_date = date('d-m-Y');
        $model->payment_exchange_rate = 1;

        $items = array();

        if ($model->load(Yii::$app->request->post())) {
            $noProblem = true;

            $items = $_POST['item'];
            
            $trans = Yii::$app->db->beginTransaction();

            $model->payment_date = !empty($model->payment_date) ? date("Y-m-d", strtotime($model->payment_date)) : NULL;    
            
            $model->payment_code = $model->getLatestNumber('SP', 'payment_code');

            if($model->save()){
                $noItem = true;
                foreach($items as $i => $item) {
                    if ($i !== "{index}") {
                        $noItem = false;
                        $temp = new SalesPaymentDetail;
                        $temp->payment_id = $model->id;
                        $temp->payment_detail_amount = $item['payment_detail_amount'];
                        $temp->payment_detail_method = $item['payment_detail_method'];
                        $temp->bank_id = $item['bank_id'];
                        $temp->payment_detail_bank_acc_number = $item['payment_detail_bank_acc_number'];
                        $temp->payment_detail_bank_acc_name = $item['payment_detail_bank_acc_name'];
                        $temp->payment_detail_debitcard_number = $item['payment_detail_debitcard_number'];
                        $temp->payment_detail_creditcard_number = $item['payment_detail_creditcard_number'];
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

                $invoice = SalesInvoice::find()->andWhere('id=:id', [':id' => $model->invoice_id])->one();

                $amount = $model->currencyRptoUsd($model->payment_exchange_rate, $model->payment_total_amount, $invoice->invoice_exchange_rate);
                // print_r($amount);
                // exit();
                $invoice->invoice_outstanding_amount -= $amount;
                
                if ($invoice->invoice_outstanding_amount < 0) {
                    $invoice->invoice_outstanding_amount = 0;
                }
                // else {
                    if (!$invoice->update()) {
                        $errorString = ErrorGenerateComponent::generateErrorLabels($invoice->getErrors());
                        Yii::$app->session->setFlash('danger', 'Gagal mengubah jumlah belum terbayar di nota karena kesalahan berikut: '.$errorString);
                        $noProblem = false;
                    }else{

                        $invoice->updatePaymentStatus($model->invoice_id);
                    }
                // }

            }
            else{
                $errorMessage = ErrorGenerateComponent::generateErrorLabels($model->getErrors());
                $noProblem = false;
                Yii::$app->session->setFlash('danger', $errorMessage);
            }

            if ($noProblem) {
                $trans->commit();
                Yii::$app->session->setFlash('success', 'Pembayaran '.LabelComponent::SUCCESS_SAVE);
                return $this->redirect(['index']);
            }

            $trans->rollback();
			$model->payment_date = !empty($model->payment_date) ? date("d-m-Y", strtotime($model->payment_date)) : NULL;
        }

        return $this->render('form', [
            'model' => $model,
            'mode' => 'create',
            'items' => $items,
        ]);
    }

    public function actionUpdate($id)
    {
        BaseController::$page_caption = 'Update Pembayaran';
        $model = $this->findModel($id);
        $model->payment_date = !empty($model->payment_date) ? date("d-m-Y", strtotime($model->payment_date)) : NULL;    
        

        $items = array();
        foreach($model->salesPaymentDetails as $item) {
            $items[] = [
                'payment_id' => $item->payment_id,
                'payment_detail_method' => $item->payment_detail_method,
                'payment_detail_amount' => $item->payment_detail_amount,
                'bank_id' => $item->bank_id,
                'payment_detail_bank_acc_name' => $item->payment_detail_bank_acc_name,
                'payment_detail_bank_acc_number' => $item->payment_detail_bank_acc_number,
                'payment_detail_creditcard_number' => $item->payment_detail_creditcard_number,
                'payment_detail_debitcard_number' => $item->payment_detail_debitcard_number,
            ];
        }

        if ($model->load(Yii::$app->request->post())) {
            $noProblem = true;

            $items = $_POST['item'];
            
            $trans = Yii::$app->db->beginTransaction();

            $model->payment_date = !empty($model->payment_date) ? date("Y-m-d", strtotime($model->payment_date)) : NULL;    
            
            // $model->payment_code = $model->getLatestNumber('SP', 'payment_code');

            if($model->save()){
                $noItem = true;

                $model->updateOutstanding($model->invoice_id);
                SalesPaymentDetail::deleteAll('payment_id=:id', [':id' => $id]);

                foreach($items as $i => $item) {
                    if ($i !== "{index}") {
                        $noItem = false;
                        $temp = new SalesPaymentDetail;
                        $temp->payment_id = $model->id;
                        $temp->payment_detail_amount = $item['payment_detail_amount'];
                        $temp->payment_detail_method = $item['payment_detail_method'];
                        $temp->bank_id = $item['bank_id'];
                        $temp->payment_detail_bank_acc_number = $item['payment_detail_bank_acc_number'];
                        $temp->payment_detail_bank_acc_name = $item['payment_detail_bank_acc_name'];
                        $temp->payment_detail_debitcard_number = $item['payment_detail_debitcard_number'];
                        $temp->payment_detail_creditcard_number = $item['payment_detail_creditcard_number'];
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

                $invoice = SalesInvoice::find()->andWhere('id=:id', [':id' => $model->invoice_id])->one();

                $amount = $model->currencyRptoUsd($model->payment_exchange_rate, $model->payment_total_amount, $invoice->invoice_exchange_rate);
                // print_r($amount);
                // exit();
                $invoice->invoice_outstanding_amount -= $amount;
                
                if ($invoice->invoice_outstanding_amount < 0) {
                    $invoice->invoice_outstanding_amount = 0;
                }
                // else {
                    if (!$invoice->update()) {
                        $errorString = ErrorGenerateComponent::generateErrorLabels($invoice->getErrors());
                        Yii::$app->session->setFlash('danger', 'Gagal mengubah jumlah belum terbayar di nota karena kesalahan berikut: '.$errorString);
                        $noProblem = false;
                    }else{

                        $invoice->updatePaymentStatus($model->invoice_id);
                    }
                // }

            }
            else{
                $errorMessage = ErrorGenerateComponent::generateErrorLabels($model->getErrors());
                $noProblem = false;
                Yii::$app->session->setFlash('danger', $errorMessage);
            }

            if ($noProblem) {
                $trans->commit();
                Yii::$app->session->setFlash('success', 'Pembayaran '.LabelComponent::SUCCESS_SAVE);
                return $this->redirect(['index']);
            }

            $trans->rollback();
            $model->payment_date = !empty($model->payment_date) ? date("d-m-Y", strtotime($model->payment_date)) : NULL;
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
            Yii::$app->session->setFlash('success', 'Sales Payment '.LabelComponent::SUCCESS_DELETE);
            $invoice = SalesInvoice::find()->andWhere('id=:id', [':id' => $model->invoice_id])->one();
            $invoice->invoice_outstanding_amount = $invoice->invoice_grand_total;
            if (!$invoice->update()) {
                $errorString = ErrorGenerateComponent::generateErrorLabels($invoice->getErrors());
                Yii::$app->session->setFlash('danger', 'Gagal mengubah jumlah belum terbayar di nota karena kesalahan berikut: '.$errorString);
                $noProblem = false;
            }else{

                $invoice->updatePaymentStatus($model->invoice_id);
            }
        }
        else {
            $errorString = ErrorGenerateComponent::generateErrorLabels($model->getErrors());
            Yii::$app->session->setFlash('danger', 'Sales Payment '.LabelComponent::ERROR_DELETE.'.'.$errorString."Silakan update dulu dan coba hapus lagi");
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
        if (($model = SalesPayment::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('Pembayaran '.LabelComponent::NOT_FOUND);
    }
}
