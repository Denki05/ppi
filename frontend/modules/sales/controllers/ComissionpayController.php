<?php

namespace frontend\modules\sales\controllers;

use Yii;
use common\models\ComissionPay;
use common\models\ComissionPayDetail;
use common\models\SalesInvoiceItem;
use common\models\SalesInvoice;
use common\models\Category;
use common\models\Customer;
use common\models\Product;
use common\models\Employee;
use frontend\models\ComissionPaySearch;
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
class ComissionpayController extends BaseController
{
   
    public function actionIndex()
    {
        BaseController::$page_caption = 'Pencairan Komisi';
        
        $searchModel = new ComissionPaySearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    // public function actionIndex2()
    // {
    //     BaseController::$page_caption = 'Pencairan Komisi';
        
    //     $searchModel = new SalesInvoiceSearch();
    //     $searchModel->invoice_payment_status = SalesInvoice::STATUS_PAYMENT_PAID;
    //     $searchModel->proses_comission_pay = 1;
    //     $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

    //     return $this->render('index', [
    //         'searchModel' => $searchModel,
    //         'dataProvider' => $dataProvider,
    //     ]);
    // }

    public function actionView($id)
    {
        BaseController::$page_caption = 'Lihat Pecairan Komisi';
        
        $model = $this->findModel($id);

        return $this->render('view', [
            'model' => $model
        ]);
    }

    public function actionGetsalesman($periode){
        $endyear = date("Y-12-31");
        $newyear = date("Y-01-01");
        
        $sales = Employee::find()->all();
        $hsl = array();
        foreach ($sales as $item) {
            $salesman = ComissionPay::find()
            ->where('comission_pay_periode = :p AND salesman_id=:id AND is_deleted=:is', [':p' => $periode, ':id' => $item->id, ':is' => 0 ])
            ->andWhere(['between','comission_pay_date',$newyear,$endyear])->one();
            
            if(!$salesman){
                $hsl[] = [
                    'id' => $item->id,
                    'employee_name' => $item->employee_name,
                ];
            }
        }

        return Json::encode($hsl);
        die();

    }

    public function actionGetinvoice($id, $periode)
    {
        $start = "";
        $end = "";
        if($periode == 'maret'){
            $start = date("Y-01-01");
            $end = date("Y-03-t");
        }
        elseif($periode == 'juni'){
            $start = date("Y-04-01");
            $end = date("Y-06-t");
        }
        if($periode == 'september'){
            $start = date("Y-07-01");
            $end = date("Y-09-t");
        }
        else{
            $start = date("Y-10-01");
            $end = date("Y-12-t");
        }

        $items = SalesInvoice::find()->where('salesman_id=:id',[':id'=>$id])->andWhere('invoice_payment_status = :p', [':p' => SalesInvoice::STATUS_PAYMENT_PAID])
        ->andWhere('is_deleted=:is', [':is' => 0])->andWhere(['between', 'invoice_date', $start, $end])->orderBy(['invoice_code' => SORT_ASC])->all();

        $hasil = array();
        foreach ($items as $item) {
            $hasil[] = [
                'id' => $item->id,
                'invoice_code' => $item->invoice_code,
                'customer_name' => $item->customer->customerName,
                'invoice_date' => date("d-m-Y", strtotime($item->invoice_date)),
                'invoice_exchange_rate' => $item->invoice_exchange_rate,
                'invoice_comission_value' => $item->invoice_comission_value,
                'payment_date' => date("d-m-Y", strtotime($item->getLastPayment())),
            ];
        }
        
        return Json::encode($hasil);
        die();
    }

    public function actionCreate()
    {
        BaseController::$page_caption = 'Tambah Pencairan Komisi';

        $model = new ComissionPay();
        $model->comission_pay_date = date('d-m-Y');
        $model->comission_pay_exchange_rate = 1;
        $items = array();

        if ($model->load(Yii::$app->request->post())) {
            $noProblem = true;

            $items = $_POST['item'];
            
            $trans = Yii::$app->db->beginTransaction();

            $model->comission_pay_date = !empty($model->comission_pay_date) ? date("Y-m-d", strtotime($model->comission_pay_date)) : NULL;    
            
            if($model->save()){
                $noItem = true;
                foreach($items as $i => $item) {
                    if ($i !== "{index}") {
                        $noItem = false;
                        $temp = new ComissionPayDetail;
                        $temp->comission_pay_id = $model->id;
                        $temp->invoice_id = $item['invoice_id'];
                        $temp->comission_pay_detail_percent = $item['comission_percent'];
                        $temp->comission_pay_detail_amount = $item['comission_amount'];
                        
                        if (!$temp->save()) {
                            $errorMessage = ErrorGenerateComponent::generateErrorLabels($temp->getErrors());
                            $noProblem = false;
                            Yii::$app->session->setFlash('danger', $errorMessage);
                        }
                    }
                }

                if ($noItem) {
                    Yii::$app->session->setFlash('danger', 'Tidak ada Nota Yang ada Komisi');
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
                Yii::$app->session->setFlash('success', 'Pembayaran '.LabelComponent::SUCCESS_SAVE);
                return $this->redirect(['index']);
            }

            $trans->rollback();
            $model->comission_pay_date = !empty($model->comission_pay_date) ? date("d-m-Y", strtotime($model->comission_pay_date)) : NULL;
        }

        return $this->render('form', [
            'model' => $model,
            'mode' => 'create',
            'items' => $items,
        ]);
    }

    public function actionUpdate($id)
    {
        BaseController::$page_caption = 'Update Pencairan Komisi';
        $model = $this->findModel($id);
        $model->comission_pay_date = !empty($model->comission_pay_date) ? date("d-m-Y", strtotime($model->comission_pay_date)) : NULL;    
        

        $items = array();
        
        if ($model->load(Yii::$app->request->post())) {
            $noProblem = true;

            $items = $_POST['item'];
            
            $trans = Yii::$app->db->beginTransaction();

            $model->comission_pay_date = !empty($model->comission_pay_date) ? date("Y-m-d", strtotime($model->comission_pay_date)) : NULL;    
            
            if($model->save()){
                $noItem = true;
                ComissionPayDetail::deleteAll('comission_pay_id=:id', [':id' => $id]);
                foreach($items as $i => $item) {
                    if ($i !== "{index}") {
                        $noItem = false;
                        $temp = new ComissionPayDetail;
                        $temp->comission_pay_id = $model->id;
                        $temp->invoice_id = $item['invoice_id'];
                        $temp->comission_pay_detail_percent = $item['comission_percent'];
                        $temp->comission_pay_detail_amount = $item['comission_amount'];
                        
                        if (!$temp->save()) {
                            $errorMessage = ErrorGenerateComponent::generateErrorLabels($temp->getErrors());
                            $noProblem = false;
                            Yii::$app->session->setFlash('danger', $errorMessage);
                        }
                    }
                }

                if ($noItem) {
                    Yii::$app->session->setFlash('danger', 'Tidak ada Nota Yang ada Komisi');
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
                Yii::$app->session->setFlash('success', 'Pembayaran '.LabelComponent::SUCCESS_SAVE);
                return $this->redirect(['index']);
            }

            $trans->rollback();
            $model->comission_pay_date = !empty($model->comission_pay_date) ? date("d-m-Y", strtotime($model->comission_pay_date)) : NULL;
        }

        return $this->render('form', [
            'model' => $model,
            'mode' => 'update',
            'items' => $items,
        ]);
        
    }
    
    
    // public function actionPay($id, $percent)
    // {

    //     if(empty($percent)){
    //         Yii::$app->session->setFlash('danger', 'diskon harus terisi terlebih dahulu !');
    //         return $this->redirect(['index']);
    //     }

    //     $model = $this->findModel($id);
    //     $model->invoice_comission_pay_date = date('Y-m-d');
    //     $model->invoice_comission_pay_percent = $percent;
    //     if(empty($model->invoice_comission_value))
    //         $model->invoice_comission_value = 0;
    //     $model->invoice_comission_pay_amount = ($model->invoice_comission_value * $model->invoice_comission_pay_percent) / 100;
        
    //     if(!$model->save()){
    //         $errorMessage = ErrorGenerateComponent::generateErrorLabels($model->getErrors());
    //         Yii::$app->session->setFlash('danger', $errorMessage);
    //     }

    //     return $this->redirect(['index']);
    // }

    public function actionDelete($id)
    {
        $model  = $this->findModel($id);
        $model->is_deleted = 1;

        if (!$model->save()){
            
            $errorString = ErrorGenerateComponent::generateErrorLabels($model->getErrors());
            Yii::$app->session->setFlash('danger', 'Pencairan Komisi '.LabelComponent::ERROR_DELETE.'.'.$errorString."Silakan update dulu dan coba hapus lagi");
        }

        return $this->redirect(['index']);
    }

    
    protected function findModel($id)
    {
        if (($model = ComissionPay::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('Pencairan Komisi '.LabelComponent::NOT_FOUND);
    }
}

