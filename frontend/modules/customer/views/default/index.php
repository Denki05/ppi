<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use yii\widgets\Pjax;
use yii\grid\GridView;
use frontend\components\LabelComponent;
use frontend\components\AccessComponent;
use common\components\CurrencyComponent;
use frontend\components\ButtonComponent;
use app\components\BaseController;
use common\models\Customer;
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
                                    'customer_store_name',
                                    'customer_store_address',
                                    'customer_city',
                                    [
                                        'attribute' => 'customer_type',
                                        'value' => function($model){
                                            return $model->getCustomerType($model->customer_type);
                                        },
                                        'filter' => (new Customer)->getCustomerType(),
                                    ], 
                                    [
                                        'attribute' => 'customer_tempo_limit',
                                        'value' => function($model){
                                            return isset($model->customer_tempo_limit) ? $model->customer_tempo_limit : '-';
                                        },
                                    ],
                                    'customer_owner_name',
                                    [
                                        'attribute' => 'customer_phone1',
                                        'label' => 'Telepon',
                                        'format' => 'raw',
                                        'value' => function($model){
                                            return $model->customerPhone;
                                        },
                                    ],
						            [
                                        'class' => 'yii\grid\ActionColumn',
                                        'header'   => 'Actions',
                                        'template' => '<div style="white-space: nowrap;">{view}{update}{delete}</div>',
                                        'buttons'  => [
                                            'view' => function ($url, $model) {
                                                if (AccessComponent::hasAccess(Yii::$app->controller->module->id, Yii::$app->controller->id, 'view')) {
                                                    $url = Url::to(['view', 'id' => $model->id]);
                                                    return Html::a('<span class="btn btn-outline-success btn-sm"><i class="la la-eye"></i></span> ', $url, ['title' => 'View']);
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
