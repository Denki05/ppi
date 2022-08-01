<?php

namespace frontend\modules\report\controllers;

use Yii;
use common\models\ComissionPay;
use common\models\ComissionPayDetail;
use common\models\SalesInvoiceItem;
use common\models\SalesInvoice;
use common\models\Category;
use common\models\Customer;
use common\models\Product;
use common\models\Employee;
use frontend\models\ComissionReport;
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
class DefaultController extends BaseController
{
   
    public function actionIndex()
    {
        BaseController::$page_caption = 'Laporan Komisi Sales';
        
        $searchModel = new ComissionReport();
        if (isset($_GET['ComissionReport']))
            $searchModel->attributes = $_GET['ComissionReport'];
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        // print_r($dataProvider);
        // exit();
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionExportpdf()
    {
        $searchModel = new ComissionReport();
        if (isset($_GET['ComissionReport']))
            $searchModel->attributes = $_GET['ComissionReport'];
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams, false);
       
            $content = $this->renderPartial('exportpdf', [
                'searchModel' => $searchModel,
                'dataProvider' => $dataProvider
            ]);
            
            $pdf = new Pdf([
                'mode' => Pdf::MODE_CORE, 
                
                'format' => 'A5', 
                'orientation' => Pdf::ORIENT_PORTRAIT, 
                'destination' => Pdf::DEST_DOWNLOAD,//Pdf::DEST_BROWSER, 
                'content' => $content,
                'filename' => "laporan_komisi_sales_".date('d-m-Y-His').".pdf",
                //'cssFile' => '@vendor/kartik-v/yii2-mpdf/src/assets/kv-mpdf-bootstrap.min.css',
                'marginLeft' => 5,
                'marginRight' => 5,
                'marginTop' => 5,
                'marginBottom' => 5,
                'cssInline' => '
                    body {font-size: 11px;}
                    table {width: 100%;}
                    .col-left {width: 60%; vertical-align: top; padding: 0;}
                    .col-right {width: 40%; text-align: right; padding: 0;}
                    .store-name {font-size: 15px; font-weight: bold; padding-bottom: 20px; display: block;}
                    .store-logo {margin-bottom: 50px;}
                    .date {font-weight: bold;}
                    .pdf-title {font-size: 20px; font-weight: bold; width: 100%; text-align: right; border-bottom: 1px solid #000; margin-bottom: 5px;}
                    .customer-title {font-weight: bold;}
                    .table-product td {padding: 2px}
                ',
                'options' => [
                    'title' => 'Laporan Komisi Sales',
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

    public function actionExportexcel()
    {
        $searchModel = new ComissionReport();
        if (isset($_GET['ComissionReport']))
            $searchModel->attributes = $_GET['ComissionReport'];
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams, false);
        ComissionReport::getExcel($dataProvider->models);
        
        return $this->redirect(['index']);
    }

    public function actionView($id)
    {
        BaseController::$page_caption = 'Lihat Pecairan Komisi';
        
        $model = $this->findModel($id);

        return $this->render('view', [
            'model' => $model
        ]);
    }

}

