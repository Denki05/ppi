<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use yii\widgets\Pjax;
use yii\grid\GridView;
use frontend\components\LabelComponent;
use frontend\components\ButtonComponent;
use frontend\components\AccessComponent;
use common\components\CurrencyComponent;
use app\components\BaseController;
// use kartik\grid\GridView;

$this->title = BaseController::getCustomPageTitle(BaseController::$page_caption);
$toolbar[] = ButtonComponent::getBackUpButton();

BaseController::$toolbar = $toolbar;

foreach(Yii::$app->session->getAllFlashes() as $key => $message)
    echo '<div class="alert alert-' . $key . '">' . $message . "</div>\n";
?>

<section class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-content">
                <div class="card-body">
                    <div class="table-responsive">
                    <?php Pjax::begin();?>
                        <?= GridView::widget([
                            'dataProvider' => $dataProvider,
                            'summary' => LabelComponent::GRID_SUMMARY,
                            'columns' => [
                                'name',
                                'size:size',
                                'create_time',
                                'modified_time:relativeTime',
                                [
                                    'class' => 'yii\grid\ActionColumn',
                                    'header'   => 'Actions',
                                    'template' => '<div>{download} {delete_action}</div>',
                                    'buttons'  => [
                                        // 'download' => function ($url, $model){
                                        //     $url = Url::to(['download', 'file' => $model['name']]);
                                        //     return Html::a('<span class="btn btn-outline btn-primary dim btn-xs glyphicon glyphicon-download"></span>', $url, ['title' => 'download', 'data-pjax' => 0]);
                                        // },
                                        // 'restore_action' => function ($url, $model) {
                                        //     $url = Url::to(['restore', 'filename' => $model['name']]);
                                        //     return Html::a('<span class="btn btn-outline btn-warning dim btn-xs glyphicon glyphicon-repeat"></span>', $url, [
                                        //         'title' => 'Restore','class'=>'restore',
                                        //         'data-confirm' => Yii::t('yii', 'Apakah Anda yakin akan merestore menggunakan back up ini?'),
                                        //             'data-pjax' => '0',
                                        //     ]);
                                        // },
                                        // 'delete_action' => function ($url, $model) {
                                        //     $url = Url::to(['delete', 'filename' => $model['name']]);
                                        //     return Html::a('<span class="btn btn-outline btn-danger dim btn-xs glyphicon glyphicon-trash"></span>', $url, [
                                        //         'title' => Yii::t('app', 'Delete Database'),'class'=>'delete',
                                        //     ]);
                                        // }

                                        'download' => function ($url, $model) {
                                                $url = Url::to(['download', 'file' => $model['name']]);
                                                return Html::a('<span class="btn btn-outline-warning btn-sm"><i class="la la-download"></i></span> ', $url, ['title' => 'download', 'data-pjax' => 0]);
                                        },
                                        'delete_action' => function ($url, $model) {
                                            // if (AccessComponent::hasAccess(Yii::$app->controller->module->id, Yii::$app->controller->id, 'delete')) {
                                                $url = Url::to(['delete', 'filename' => $model['name']]);
                                                return Html::a(
                                                    '<span class="btn btn-outline-danger btn-sm"><i class="la la-trash"></i></span>',
                                                    '#',
                                                    [
                                                        'title' => Yii::t('yii', 'Delete'),
                                                        'aria-label' => Yii::t('yii', 'Delete'),
                                                        'onclick' => "deleteclick('".$url."');"
                                                    ]
                                                );
                                            // }
                                            // return "";
                                        }
                                    ],
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
    
</script>
