<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use yii\widgets\Pjax;
use yii\grid\GridView;
use common\models\PurchasePayment;
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

	<section class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-head">
                    
                </div>
                <div class="card-content">
                    <div class="card-body">
                        <div class="table-responsive">
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
                                    [
                                        'attribute' => 'purchase_payment_date',
                                        'value' => function($model) {
                                            return DateComponent::getHumanizedDate($model->purchase_payment_date);
                                        },
                                        'filter' => Html::activeTextInput($searchModel, 'start_date', ['class' => 'form-control dropicker'])." hingga ".Html::activeTextInput($searchModel, 'end_date', ['class' => 'form-control dropicker'])
                                    ],
                                    [
                                        'attribute' => 'purchase_order_code',
                                        'value' => function($model) {
                                            return $model->purchaseOrder->purchase_order_code;
                                        }
                                    ],
                                    [
                                        'attribute' => 'supplier_id',
                                        'value' => function($model) {
                                            return $model->supplier->supplier_name;
                                        }
                                    ],
                                    [
                                        'attribute' => 'purchase_payment_total_amount',
                                        'value' => function($model) {
                                            return CurrencyComponent::formatMoney($model->purchase_payment_total_amount,0,',','.', Product::CURRENCY_DOLAR);
                                        },
                                        //'filter' => Html::activeTextInput($searchModel, 'start_grand_total', ['class' => 'form-control'])." hingga ".Html::activeTextInput($searchModel, 'end_grand_total', ['class' => 'form-control'])
                                        'filter' => false,
                                    ],
						            [
                                        'class' => 'yii\grid\ActionColumn',
                                        'header'   => 'Actions',
                                        'template' => '<div style="white-space: nowrap;">{view}{update}{delete}</div>',
                                        'buttons'  => [
                                            'view' => function ($url, $model) {
                                                if (AccessComponent::hasAccess(Yii::$app->controller->module->id, Yii::$app->controller->id, 'view')) {
                                                    $url = Url::to(['view', 'id' => $model->id]);
                                                    return Html::a('<span class="btn btn-outline-success btn-sm"><i class="la la-eye"></i></span> ', $url, ['title' => 'Update']);
                                                }
                                                return "";
                                            },
                                            'update' => function ($url, $model) {
                                                if (AccessComponent::hasAccess(Yii::$app->controller->module->id, Yii::$app->controller->id, 'update')) {
                                                    $url = Url::to(['update', 'id' => $model->id]);
                                                    return Html::a('<span class="btn btn-outline-warning btn-sm"><i class="la la-pencil"></i></span> ', $url, ['title' => 'Update']);
                                                }
                                                return "";
                                            },
                                            'delete' => function ($url, $model) {
                                                if (AccessComponent::hasAccess(Yii::$app->controller->module->id, Yii::$app->controller->id, 'delete')) {
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
                                                return "";
                                            }
                                        ],
                                        'headerOptions' => ['style' => 'width:7%'],
                                    ],
						        ],
						    ]); ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

<script>
    
</script>
