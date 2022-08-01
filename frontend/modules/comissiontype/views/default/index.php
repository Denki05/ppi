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
						            'comission_type_name',
                                    'comission_type_desc',
                                    'comission_type_value',
						            [
                                        'class' => 'yii\grid\ActionColumn',
                                        'header'   => 'Actions',
                                        'template' => '<div style="white-space: nowrap;">{update}{delete}</div>',
                                        'buttons'  => [
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
