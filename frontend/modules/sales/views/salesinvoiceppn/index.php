<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use yii\widgets\Pjax;
use yii\grid\GridView;
use common\models\SalesInvoice;
use frontend\components\LabelComponent;
use frontend\components\AccessComponent;
use common\components\CurrencyComponent;
use common\components\DateComponent;
use frontend\components\ButtonComponent;
use app\components\BaseController;
use common\models\Product;
// use kartik\grid\GridView;

$this->title = BaseController::getCustomPageTitle(BaseController::$page_caption);
$toolbar[] = ButtonComponent::getAddButton();

BaseController::$toolbar = $toolbar;

foreach(Yii::$app->session->getAllFlashes() as $key => $message)
    echo '<div class="alert alert-' . $key . '">' . $message . "</div>\n";
?>
<input id="baseUrl" type="hidden" value="<?=Url::base()?>/sales/salesinvoiceppn/">
	<section class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-head">
                    
                </div>
                <div class="card-content">
                    <div class="card-body">
                        <div class="table-responsive">
                           <?php Pjax::begin();?> 
                            <?= GridView::widget([
						        'dataProvider' => $dataProvider,
						        'filterModel' => $searchModel,
						        'formatter' => ['class' => 'yii\i18n\Formatter','nullDisplay' => ''],

                                // 'id' => 'disc-master-grid',
                                // 'dataProvider' => $dataProvider,
                                // 'filterModel' => $searchModel,
                                // 'bordered' => true, 
                                // 'responsiveWrap' => true,
                                // 'condensed' => true,
                                // 'pjax' => true,
                                // 'pager' => [
                                //     'firstPageLabel' => 'First',
                                //     'lastPageLabel'  => 'Last'
                                // ],
						        'columns' => [
						            ['class' => 'yii\grid\SerialColumn'],
						            'invoice_code_ppn',
                                    [
                                        'attribute' => 'invoice_date',
                                        'value' => function($model) {
                                            return DateComponent::getHumanizedDate($model->invoice_date);
                                        },
                                        'filter' => Html::activeTextInput($searchModel, 'start_date', ['class' => 'form-control dropicker'])." hingga ".Html::activeTextInput($searchModel, 'end_date', ['class' => 'form-control dropicker'])
                                    ],
                                    [
                                        'attribute' => 'customer_name',
                                        'value' => function($model) {
                                            return isset($model->customer) ? $model->customer->customerName : "";
                                        }
                                    ],
                                    [
                                        'attribute' => 'salesman_name',
                                        'value' => function($model) {
                                            return isset($model->salesman) ? $model->salesman->employee_name : "";
                                        }
                                    ],
                                    [
                                        'attribute' => 'invoice_grand_total',
                                        'value' => function($model) {
                                            if($model->invoice_exchange_rate > 1)
                                                return CurrencyComponent::formatMoney($model->invoice_grand_total);
                                            else
                                                return CurrencyComponent::formatMoney($model->invoice_grand_total,0,',','.', Product::CURRENCY_DOLAR);
                                        },
                                        //'filter' => Html::activeTextInput($searchModel, 'start_grand_total', ['class' => 'form-control'])." hingga ".Html::activeTextInput($searchModel, 'end_grand_total', ['class' => 'form-control'])
                                        'filter' => false,
                                    ],
                                    [
                                        'attribute' => 'invoice_outstanding_amount',
                                        'value' => function($model) {
                                            if($model->invoice_exchange_rate > 1)
                                                return CurrencyComponent::formatMoney($model->invoice_outstanding_amount);
                                            else
                                                return CurrencyComponent::formatMoney($model->invoice_outstanding_amount,0,',','.', Product::CURRENCY_DOLAR);
                                        },
                                        //'filter' => Html::activeTextInput($searchModel, 'start_outstanding', ['class' => 'form-control'])." hingga ".Html::activeTextInput($searchModel, 'end_outstanding', ['class' => 'form-control'])
                                        'filter' => false,
                                    ],
                                    [
                                        'attribute' => 'invoice_status',
                                        'value' => function($model) {
                                            return (new SalesInvoice)->getStatusLabel($model->invoice_status);
                                        },
                                        'filter' => (new SalesInvoice)->getStatusLabel()
                                    ],
                                    [
                                        'attribute' => 'invoice_payment_status',
                                        'value' => function($model) {
                                            return (new SalesInvoice)->getStatusPayment($model->invoice_payment_status);
                                        },
                                        'filter' => (new SalesInvoice)->getStatusPayment()
                                    ],
						            [
                                        'class' => 'yii\grid\ActionColumn',
                                        'header'   => 'Actions',
                                        'template' => '<div style="white-space: nowrap; margin-bottom: 5px;">{view}{export}{print}</div><div style="white-space: nowrap;">{update}{delete}</div>',
                                        'buttons'  => [
                                            'export' => function ($url, $model) {
                                                if (AccessComponent::hasAccess(Yii::$app->controller->module->id, Yii::$app->controller->id, 'export')) {
                                                    $url = Url::to(['export', 'id' => $model->id]);
                                                    $status = $model->invoice_payment_status === SalesInvoice::STATUS_PAYMENT_PAID ? 1 : 0;
                                                    return Html::a('<span class="btn btn-outline-info btn-sm"><i class="la la-print"></i></span> ', '#', ['title' => 'Export PDF', 'class' => 'btn-print', 'id' => 'id-invoice-'.$model->id.'-'.$status]);
                                                }
                                                return "";
                                            },
                                            'print' => function ($url, $model) {
                                                if (AccessComponent::hasAccess(Yii::$app->controller->module->id, Yii::$app->controller->id, 'print')) {
                                                    // $url = Url::to(['export', 'id' => $model->id]);
                                                    // $status = $model->invoice_payment_status === SalesInvoice::STATUS_PAYMENT_PAID ? 1 : 0;
                                                    return Html::a('<span class="btn btn-outline-dark btn-sm"><i class="la la-print"></i></span> ', '#', ['title' => 'Print Surat Jalan', 'class' => 'btn-print-delivery', 'id' => "btn-print-".$model->id]);
                                                }
                                                return "";
                                            },
                                            'view' => function ($url, $model) {
                                                if (AccessComponent::hasAccess(Yii::$app->controller->module->id, Yii::$app->controller->id, 'view')) {
                                                    $url = Url::to(['view', 'id' => $model->id]);
                                                    return Html::a('<span class="btn btn-outline-success btn-sm"><i class="la la-eye"></i></span> ', $url, ['title' => 'Update']);
                                                }
                                                return "";
                                            },
                                            'update' => function ($url, $model) {
                                                if (AccessComponent::hasAccess(Yii::$app->controller->module->id, Yii::$app->controller->id, 'update')) {
                                                    if(!$model->isPayment()){
                                                        $url = Url::to(['update', 'id' => $model->id]);
                                                        return Html::a('<span class="btn btn-outline-warning btn-sm"><i class="la la-pencil"></i></span> ', $url, ['title' => 'Update']);
                                                    }
                                                }
                                                return "";
                                            },
                                            'delete' => function ($url, $model) {
                                                if (AccessComponent::hasAccess(Yii::$app->controller->module->id, Yii::$app->controller->id, 'delete')) {
                                                    if(!$model->isPayment()){
                                                        $url = Url::to(['delete', 'id' => $model->id]);
                                                        return Html::a(
                                                            '<span class="btn btn-outline-danger btn-sm"><i class="la la-trash"></i></span>',
                                                            '#',
                                                            [
                                                                'title' => Yii::t('yii', 'Delete'),
                                                                'aria-label' => Yii::t('yii', 'Delete'),
                                                                'onclick' => "deleteclick('".$url."');"
                                                            ]
                                                        );
                                                    }
                                                }
                                                return "";
                                            }
                                        ],
                                        'headerOptions' => ['style' => 'width:7%'],
                                    ],
						        ],
						    ]); ?>
                            <?php Pjax::end();?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

<script>
$(document).on('click', '.btn-print', function() {
    var id = $(this).attr("id").split("-")[2];
    var status = $(this).attr("id").split("-")[3];
    var url = $('#baseUrl').val();

    window.location.href = url+"export?id="+id;
    if(status == 1){
        setTimeout(function(){ 
            window.location.href = url+"export2?id="+id;
        }, 2000);
    }
});

$(document).on("click", ".btn-print-delivery", function(e){
    e.preventDefault();

    var id = $(this).attr("id").split("-")[2];
    // alert(base3);
    $.post(base3+'/sales/salesinvoiceppn/print', {'invoiceId': id}, function(response) {
        $.post("http://localhost/ppiprint/index.php", {'slipContent': response}, function(result) {
            console.log(result);
        });
    }, 'json');
});
</script>