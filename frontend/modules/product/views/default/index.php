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
use common\models\Product;
// use kartik\grid\GridView;

$this->title = BaseController::getCustomPageTitle(BaseController::$page_caption);
// if (AccessComponent::hasAccess(Yii::$app->controller->module->id, Yii::$app->controller->id, 'create'))
    $toolbar[] = ButtonComponent::getAddButton();
    $toolbar[] = ButtonComponent::getImportButton();

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
                                        'attribute' => 'product_material_code',
                                        'format' => 'raw',
                                        'value' => function($model){
                                            return !empty($model->product_material_code) ? $model->product_material_code : '<center>-</center>';
                                        },
                                    ],
                                    [
                                        'attribute' => 'product_material_name',
                                        'format' => 'raw',
                                        'value' => function($model){
                                            return !empty($model->product_material_name) ? $model->product_material_name : '<center>-</center>';
                                        },
                                    ],
                                    'product_code',
                                    'product_name',
                                    [
                                        'attribute' => 'factory_name',
                                        'value' => function($model){
                                            return $model->factory->factory_name;
                                        },
                                    ],
                                    [
                                        'attribute' => 'brand_name',
                                        'value' => function($model){
                                            return $model->brand->brand_name;
                                        },
                                    ],
                                    [
                                        'attribute' => 'category_name',
                                        'format' => 'raw',
                                        'value' => function($model){
                                            return !empty($model->category->category_name) ? $model->category->category_name : '<center>-</center>';
                                        },
                                    ],
                                    [
                                        'attribute' => 'product_status',
                                        'value' => function($model){
                                            return $model->getProductStatus($model->product_status);
                                        },
                                        'filter' => (new Product)->getProductStatus(),
                                    ],
                                    [
                                        'attribute' => 'product_type',
                                        'value' => function($model){
                                            return $model->getProductType($model->product_type);
                                        },
                                        'filter' => (new Product)->getProductType(),
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
