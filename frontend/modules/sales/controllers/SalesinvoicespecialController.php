<?php

namespace frontend\modules\sales\controllers;

use Yii;
use common\models\SalesInvoice;
use common\models\SalesInvoiceItem;
use common\models\Category;
use common\models\Customer;
use common\models\InvoiceNote;
use common\models\Product;
use common\models\Bank;
use common\models\Brand;
use frontend\models\SalesInvoiceSearch;
// use yii\web\Controller;
use common\components\ErrorGenerateComponent;
use frontend\components\LabelComponent;
use app\components\BaseController;
use yii\web\NotFoundHttpException;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use yii\helpers\Json;
use kartik\mpdf\Pdf;
use yii\helpers\VarDumper;
use yii\data\Sort;
use kartik\select2\Select2;
use yii\db\Query;

/**
 * DefaultController implements the CRUD actions for DiscMaster model.
 */
class SalesinvoicespecialController extends BaseController
{
   
    public function actionIndex()
    {
        BaseController::$page_caption = 'Nota';
        
        $searchModel = new SalesInvoiceSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionGetbrand()
    {

        $search_reference = Yii::$app->request->post('search_reference');

        // $query = new Query;
        // $query->select('id, product_code, product_name, product_sell_price')->from('tbl_product')->where(['brand_id' => $search_reference]);
        // $rows = $query->orderBy('product_name')->all();

        $rows = Product::find()
            ->select('id, product_code, product_name, product_sell_price')
            ->where(['brand_id'=>$search_reference])
            ->andWhere('is_deleted=:is',[':is'=>0])
            ->orderBY('product_name')
            ->all();

        $data = [];
        if(!empty($rows)) {
            foreach($rows as $row) {
                $data[] = ['id' => $row['id'], 'product_code' => $row['product_code'], 'product_name' => $row['product_name'], 'product_sell_price' => $row['product_sell_price']];
            }
        } else {
            $data = '';
        }

        return $this->asJson($data);

        // $posts = \common\models\Post::find()
        //         ->where(['category_id' => $id])
        //         ->orderBy('id DESC')
        //         ->all();

        // if (!empty($posts)) {
        //     $option = '<option>-Select Option-</option>';
        //     foreach($posts as $post) {
        //         $options .= "<option value='".$post->id."'>".$post->title."</option>";
        //     }
        //     return $options;
        // } else {
        //     return "<option>-</option>";
        // }

    }

    public function actionGetitemrow($id)
    {
        $item = Product::find()
				->where(['id' => $id])
				->one();
        
        $hasil = array(
            'id' => $item->id,
            'product_name' => $item->product_name,
            'product_sell_price' => $item->product_sell_price
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

    public function actionGetbank($id)
    {
        $item = Bank::find()->where('id=:id',[':id'=>$id])->one();

        $hasil = array();
        if(!empty($item)){
            $hasil = array(
                'bank_name' => $item->bank_name,
                'bank_acc_number' => $item->bank_acc_number,
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
        BaseController::$page_caption = 'Lihat Nota';
        
        $model = $this->findModel($id);

        return $this->render('view', [
            'model' => $model
        ]);
    }

    public function actionCreate()
    {
        BaseController::$page_caption = 'Tambah Nota';

        $model = new SalesInvoice();
        $model->invoice_date = date('d-m-Y');
        $model->invoice_exchange_rate = 1;

        $items = array();

        if ($model->load(Yii::$app->request->post())) {
            $noProblem = true;

            $items = $_POST['item'];
            // $model->items = Yii::$app->request->post('Item',[]);

            $trans = Yii::$app->db->beginTransaction();

            $model->invoice_type = 'nonppn';
            $model->invoice_date = !empty($model->invoice_date) ? date("Y-m-d", strtotime($model->invoice_date)) : NULL;    
            $model->invoice_comission_pay_date = !empty($model->invoice_comission_pay_date) ? date("Y-m-d", strtotime($model->invoice_comission_pay_date)) : NULL;
            $model->invoice_code = $model->getInvoiceCode('invoice_code');
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
                // dd($model->invoice_code);
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
        // $model->invoice_product_type = $model->invoice_product_type;
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

    public function actionCheck()
    {
        // $model = SalesInvoice::find()->where(['is_deleted' => '0']);

        $model = (new \yii\db\Query())
            ->select(['tbl_sales_invoice.invoice_code', 
                        'tbl_sales_invoice_item.product_id', 
                        'tbl_product.brand_id',
                        'tbl_sales_invoice.id as invoiceID',
                        'tbl_brand.brand_name',
                        'tbl_sales_invoice.invoice_product_type',
                    ])
            ->from('tbl_sales_invoice')
            ->leftJoin('tbl_sales_invoice_item', 'tbl_sales_invoice_item.invoice_id=tbl_sales_invoice.id')
            ->leftJoin('tbl_product', 'tbl_sales_invoice_item.product_id=tbl_product.id')
            ->leftJoin('tbl_brand', 'tbl_product.brand_id=tbl_brand.id')
            ->where(['tbl_sales_invoice.is_deleted' => '0'])
            ->andWhere(['between', 'tbl_sales_invoice.invoice_date', "2021-10-01", "2021-12-31" ])
            // ->andWhere(['tbl_product.product_status' => 'active'])
            
            ->all();

        // dd($model);

        $items      = array();

        $same_brand = true;

        $noProblem  = true;

        foreach($model as $item){
            $items[] = [
                'product_id' => $item['product_id'],
                'brand_id' => $item['brand_id'],
                'invoice_code' => $item['invoice_code'],
                'brand_name' => $item['brand_name'],
            ];

            // dd($item);

            // dd($item['brand_name']);

            // $list = SalesInvoiceItem::find($item['id']);

            $list = (new \yii\db\Query())
                ->select(['tbl_sales_invoice_item.product_id', 
                            'tbl_product.brand_id',
                            'tbl_sales_invoice_item.invoice_id'
                        ])
                ->from('tbl_sales_invoice_item')
                ->leftJoin('tbl_product', 'tbl_sales_invoice_item.product_id=tbl_product.id')
                ->leftJoin('tbl_brand', 'tbl_product.brand_id=tbl_brand.id')
                ->where(['in', 'invoice_id', $item])
                ->all();

            // dd($list);

            foreach($list as $val){
                    if(in_array($val['brand_id'], $items)) {
                        $same_brand = true;

                        // $model = $this->findModel($item['invoiceID']);
                        // $model->invoice_product_type = $item['brand_name'];
                        // $model->save();

                        $model = $this->findModel($item['invoiceID']);
                        $model->invoice_product_type = $item['brand_id'];
                        
                        $model->bank_id = !empty($model->bank_id) ? $model->bank_id : NULL;

                        if (!$model->save()) {
                            $errorMessage = ErrorGenerateComponent::generateErrorLabels($model->getErrors());
                            // $noProblem = false;
                            Yii::$app->session->setFlash('danger', $errorMessage);
                        }

                        // dd($note->save());
                        
                    } else {
                        array_push($items, $val['brand_id']);
                        $same_brand = false;

                        $invoice_note = new InvoiceNote();
                        $invoice_note->invoice_id = $item['invoiceID'];
                        $invoice_note->invoice_code = $item['invoice_code'];
                        if (!$invoice_note->save()) {
                            $errorMessage = ErrorGenerateComponent::generateErrorLabels($invoice_note->getErrors());
                            // $noProblem = false;
                            Yii::$app->session->setFlash('danger', $errorMessage);
                        }
                    }

                    if ($noProblem) {
                        // $trans->commit();
                        Yii::$app->session->setFlash('success', 'Nota '.LabelComponent::SUCCESS_CHECK);
                        // return $this->redirect(['index']);
                    }
            }
        }
        return $this->redirect(['index']);
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
