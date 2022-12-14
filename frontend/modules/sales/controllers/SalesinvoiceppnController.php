<?php

namespace frontend\modules\sales\controllers;

use Yii;
use common\models\SalesInvoice;
use common\models\SalesInvoiceItem;
use common\models\Category;
use common\models\Customer;
use common\models\Product;
use frontend\models\SalesInvoiceSearchPpn;
// use yii\web\Controller;
use common\components\ErrorGenerateComponent;
use frontend\components\LabelComponent;
use app\components\BaseController;
use yii\web\NotFoundHttpException;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use yii\helpers\Json;
use kartik\mpdf\Pdf;

/**
 * DefaultController implements the CRUD actions for DiscMaster model.
 */
class SalesinvoiceppnController extends BaseController
{
   
    public function actionIndex()
    {
        BaseController::$page_caption = 'Nota PPN';
        
        $searchModel = new SalesInvoiceSearchPpn();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function getImageurl()
    {
      return \Yii::getAlias('@imageurl').'/'.$this->picture;
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

    public function actionGetcustomer($id)
    {
        $item = Customer::find()->where('id=:id',[':id'=>$id])->one();

        $hasil = array();
        $hasil = array(
            'customer_name' => isset($item->customerName) ? $item->customerName : '',
            'customer_store_postal_code' => isset($item->customer_store_postal_code) ? $item->customer_store_postal_code : '',
            'customer_store_address' => isset($item->customer_store_address) ? $item->customer_store_address : '',
            'customer_province' => isset($item->customer_province) ? $item->customer_province : '',
            'customer_city' => isset($item->customer_city) ? $item->customer_city : '',
        );
        
        return Json::encode($hasil);
        die();
    }
    
    public function actionView($id)
    {
        BaseController::$page_caption = 'Lihat Nota PPN';
        
        $model = $this->findModel($id);

        return $this->render('view', [
            'model' => $model
        ]);
    }

    public function actionCreate()
    {
        BaseController::$page_caption = 'Tambah Nota PPN';

        $model = new SalesInvoice();
        $model->invoice_date = date('d-m-Y');
        $model->invoice_exchange_rate = 1;

        $items = array();

        if ($model->load(Yii::$app->request->post())) {
            $noProblem = true;

            $items = $_POST['item'];
            
            $trans = Yii::$app->db->beginTransaction();

            $model->invoice_date = !empty($model->invoice_date) ? date("Y-m-d", strtotime($model->invoice_date)) : NULL;    
            $model->invoice_comission_pay_date = !empty($model->invoice_comission_pay_date) ? date("Y-m-d", strtotime($model->invoice_comission_pay_date)) : NULL;
            
            $model->invoice_code = $model->getInvoiceCodeP('invoice_code');
            $model->invoice_outstanding_amount = $model->invoice_grand_total;

            if(!empty($model->comission_type_id)){
                $amount = $model->invoice_grand_total;
                if($model->invoice_exchange_rate > 1)
                    $amount = $model->invoice_grand_total / $model->invoice_exchange_rate;
                $totalComission = ( $amount * $model->comissionType->comission_type_value ) / 100;
                $model->invoice_comission_value = $totalComission;
            }

            if($model->save()){
                $noItem = true;
                foreach($items as $i => $item) {
                    if ($i !== "{index}") {
                        $noItem = false;
                        $temp = new SalesInvoiceItem;
                        $temp->invoice_id = $model->id;
                        $temp->product_id = $item['product_id'];
                        $temp->packaging_id = $item['packaging_id'];
                        $temp->invoice_item_qty = $item['invoice_item_qty'];
                        $temp->invoice_item_disc_amount = $item['invoice_item_disc_amount'];
                        $temp->invoice_item_disc_percent = $item['invoice_item_disc_percent'];
                        // $temp->invoice_item_disc_amount2 = $item['invoice_item_disc_amount2'];
                        // $temp->invoice_item_disc_percent2 = $item['invoice_item_disc_percent2'];
                        $temp->invoice_item_price = $item['invoice_item_price'];
                        $temp->invoice_item_row_total = $item['invoice_item_row_total'];
                        if (!$temp->save()) {
                            $errorMessage = ErrorGenerateComponent::generateErrorLabels($temp->getErrors());
                            $noProblem = false;
                            Yii::$app->session->setFlash('danger', $errorMessage);
                        }
                    }
                }

                // dd($model->invoice_code);

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

            if($noProblem){
                
                if(!empty($_POST['is_create_po']) && $_POST['is_create_po'] == 1){
                    $po = $model->createPO($items);
                    if ($po['success'] == false) {
                        Yii::$app->session->setFlash('danger', $po['message']);
                        $noProblem = false;
                    }
                }

            }

            if ($noProblem) {
                $trans->commit();
                Yii::$app->session->setFlash('success', 'Nota '.LabelComponent::SUCCESS_SAVE);
                return $this->redirect(['index']);
            }

            $trans->rollback();
			$model->invoice_date = !empty($model->invoice_date) ? date("d-m-Y", strtotime($model->invoice_date)) : NULL;    
            $model->invoice_comission_pay_date = !empty($model->invoice_comission_pay_date) ? date("d-m-Y", strtotime($model->invoice_comission_pay_date)) : NULL;    
        }

        return $this->render('form', [
            'model' => $model,
            'mode' => 'create',
            'items' => $items,
        ]);
    }

    public function actionUpdate($id)
    {
		BaseController::$page_caption = 'Update Nota';
		
		$model = $this->findModel($id);
        $model->invoice_date = !empty($model->invoice_date) ? date("d-m-Y", strtotime($model->invoice_date)) : NULL;
        $model->invoice_comission_pay_date = !empty($model->invoice_comission_pay_date) ? date("d-m-Y", strtotime($model->invoice_comission_pay_date)) : '';    

        $items = array();

        foreach($model->salesInvoiceItems as $item) {
            $items[] = [
                'product_id' => $item->product_id,
                'packaging_id' => $item->packaging_id,
                'product_name' => $item->product->product_name,
                'invoice_item_disc_amount' => $item->invoice_item_disc_amount,
                'invoice_item_disc_percent' => $item->invoice_item_disc_percent,
                // 'invoice_item_disc_amount2' => isset($item->invoice_item_disc_amount2) ? $item->invoice_item_disc_amount2 : 0,
                // 'invoice_item_disc_percent2' => isset($item->invoice_item_disc_percent2) ? $item->invoice_item_disc_percent2 : 0,
                'invoice_item_qty' => $item->invoice_item_qty,
                'invoice_item_price' => $item->invoice_item_price,
                'invoice_item_row_total' => $item->invoice_item_row_total,
            ];
        }

        if ($model->load(Yii::$app->request->post())) {
            $noProblem = true;

            $items = $_POST['item'];
            
            $trans = Yii::$app->db->beginTransaction();

            $model->invoice_date = !empty($model->invoice_date) ? date("Y-m-d", strtotime($model->invoice_date)) : NULL;    
            $model->invoice_comission_pay_date = !empty($model->invoice_comission_pay_date) ? date("Y-m-d", strtotime($model->invoice_comission_pay_date)) : NULL;
            
            $model->invoice_outstanding_amount = $model->invoice_grand_total;

            if(!empty($model->comission_type_id)){
                $amount = $model->invoice_grand_total;
                if($model->invoice_exchange_rate > 1)
                    $amount = $model->invoice_grand_total / $model->invoice_exchange_rate;
                $totalComission = ( $amount * $model->comissionType->comission_type_value ) / 100;
                $model->invoice_comission_value = $totalComission;
            }

            if($model->save()){
                $noItem = true;
                SalesInvoiceItem::deleteAll('invoice_id=:id', [':id' => $id]);
                foreach($items as $i => $item) {
                    if ($i !== "{index}") {
                        $noItem = false;
                        $temp = new SalesInvoiceItem;
                        $temp->invoice_id = $model->id;
                        $temp->product_id = $item['product_id'];
                        $temp->packaging_id = $item['packaging_id'];
                        $temp->invoice_item_qty = $item['invoice_item_qty'];
                        $temp->invoice_item_disc_amount = $item['invoice_item_disc_amount'];
                        $temp->invoice_item_disc_percent = $item['invoice_item_disc_percent'];
                        // $temp->invoice_item_disc_amount2 = $item['invoice_item_disc_amount2'];
                        // $temp->invoice_item_disc_percent2 = $item['invoice_item_disc_percent2'];
                        $temp->invoice_item_price = $item['invoice_item_price'];
                        $temp->invoice_item_row_total = $item['invoice_item_row_total'];
                        if (!$temp->save()) {
                            $errorMessage = ErrorGenerateComponent::generateErrorLabels($temp->getErrors());
                            $noProblem = false;
                            Yii::$app->session->setFlash('danger', $errorMessage);
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
            $model->invoice_date = !empty($model->invoice_date) ? date("d-m-Y", strtotime($model->invoice_date)) : NULL;    
            $model->invoice_comission_pay_date = !empty($model->invoice_comission_pay_date) ? date("d-m-Y", strtotime($model->invoice_comission_pay_date)) : NULL;    
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

    public function actionExport($id)
    {
        $model = $this->findModel($id);
        // for($i=1; $i<=2; $i++){
            if($model->isPayment())
                $mark = 'PAID';
            else
                $mark = 'COPY';
            $content = $this->renderPartial('print', [
                'model' => $model
            ]);
            
            $pdf = new Pdf([
                'mode' => Pdf::MODE_CORE,
                'format' => [215, 165], 
                'orientation' => Pdf::ORIENT_PORTRAIT, 
                'destination' => Pdf::DEST_DOWNLOAD,//Pdf::DEST_BROWSER, 
                'content' => $content,
                'filename' => ''.$model->invoice_code.'-'.$mark.".pdf",
                //'cssFile' => '@vendor/kartik-v/yii2-mpdf/src/assets/kv-mpdf-bootstrap.min.css',
                'marginLeft' => 5,
                'marginRight' => 5,
                'marginTop' => 5,
                'marginBottom' => 1,
                'cssInline' => '
                    body {font-size: 12px;}
                    table {width: 100%;}
                    .col-left {width: 70%; vertical-align: top; padding: 0;}
                    .col-right {width: 30%; text-align: right; padding: 0;}
                    .store-name {font-size: 20px; font-weight: bold; padding-bottom: 10px; display: block;}
                    .store-logo {margin-bottom: 50px;}
                    .date {font-weight: bold;}
                    .pdf-title {font-size: 20px; font-weight: bold; width: 100%; text-align: right; border-bottom: 1px solid #000; margin-bottom: 5px;}
                    .customer-title {font-weight: bold;}
                    .table-product td {padding: 2px 0px 0px 1px;}
                    .table-product th{ border-bottom: 1px solid;}
                    .no{ border-right: 1px solid;}
                    .product-code{ border-right: 1px solid;}
                    .product-name{ border-right: 1px solid;}
                    .acuan{ border-right: 1px solid;}
                    .packaging{ border-right: 1px solid;}
                    .qty{ border-right: 1px solid;}
                    .price{ border-right: 1px solid;}
                    .discon{ border-right: 1px solid;}
                    .netto{ border-right: 1px solid;}
                    @page {
                        header: page-header;
                        footer: page-footer;
                    }
                ',
                'options' => [
                    'title' => $model->invoice_code,
                    'showWatermarkText' => true,
                ], 
                'methods' => [ 
                    'setWatermarkText'=> $mark,
                    // 'addPage' => $content,
                ]
            ]);
            
            // $pdf->render();
            // $pdf->Output();
        // }
            ob_clean(); 

        return $pdf->render();
    }

    public function actionExport2($id)
    {
        $model = $this->findModel($id);
        // for($i=1; $i<=2; $i++){
            if($model->isPayment())
                $mark = 'PAID';
            else
                $mark = 'COPY';
            $content = $this->renderPartial('print', [
                'model' => $model
            ]);
            
            $pdf = new Pdf([
                'mode' => Pdf::MODE_CORE, 
                
                'format' => [215, 165], 
                'orientation' => Pdf::ORIENT_PORTRAIT, 
                'destination' => Pdf::DEST_DOWNLOAD,//Pdf::DEST_BROWSER, 
                'content' => $content,
                'filename' => ''.$model->invoice_code.".pdf",
                //'cssFile' => '@vendor/kartik-v/yii2-mpdf/src/assets/kv-mpdf-bootstrap.min.css',
                'marginLeft' => 5,
                'marginRight' => 5,
                'marginTop' => 5,
                'marginBottom' => 1,
                'cssInline' => '
                    body {font-size: 12px;}
                    table {width: 100%;}
                    .col-left {width: 70%; vertical-align: top; padding: 0;}
                    .col-right {width: 30%; text-align: right; padding: 0;}
                    .store-name {font-size: 20px; font-weight: bold; padding-bottom: 10px; display: block;}
                    .store-logo {margin-bottom: 50px;}
                    .date {font-weight: bold;}
                    .pdf-title {font-size: 20px; font-weight: bold; width: 100%; text-align: right; border-bottom: 1px solid #000; margin-bottom: 5px;}
                    .customer-title {font-weight: bold;}
                    .table-product td {padding: 2px 0px 0px 1px;}
                    .table-product th{ border-bottom: 1px solid;}
                    .no{ border-right: 1px solid;}
                    .product-code{ border-right: 1px solid;}
                    .product-name{ border-right: 1px solid;}
                    .acuan{ border-right: 1px solid;}
                    .packaging{ border-right: 1px solid;}
                    .qty{ border-right: 1px solid;}
                    .price{ border-right: 1px solid;}
                    .discon{ border-right: 1px solid;}
                    .netto{ border-right: 1px solid;}
                    @page {
                        header: page-header;
                        footer: page-footer;
                    }
                ',
                'options' => [
                    'title' =>$model->invoice_code,
                    // 'showWatermarkText' => true,
                ], 
                'methods' => [ 
                    // 'setWatermarkText'=> $mark,
                    // 'addPage' => $content,
                ]
            ]);
            
            // $pdf->render();
            // $pdf->Output();
        // }

        return $pdf->render();
    }

    public function actionPrint()
    {
        if (isset($_POST['invoiceId'])) {
            $invoice = SalesInvoice::find()->andWhere('id=:id', [':id' => $_POST['invoiceId']])->one();
            // echo json_encode($invoice->getInvoiceContent(77, 3));
            // $salesPayment = SalesPayment::find()->andWhere('invoice_id=:id', [':id' => $_POST['invoiceId']])->one();
            // $detailId = !empty($salesPayment->id) ? $salesPayment->id : 0;
            echo json_encode($invoice->getInvoiceContent());
            die();
        }
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
        if (($model = SalesInvoice::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('Nota '.LabelComponent::NOT_FOUND);
    }
}
