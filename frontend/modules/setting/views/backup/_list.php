<?php

use yii\helpers\Html;

//use yii\grid\GridView;
use kartik\grid\GridView;
use yii\helpers\Url;
use frontend\components\LabelComponent;
use app\components\BaseController;

$this->title = BaseController::getCustomPageTitle(BaseController::$page_caption);

$toolbar = array();
$toolbar[] = '<a href="create"><button type="button" class="btn btn-info btn-sm dim"><i class="fa fa-hdd-o"></i> Backup</button></a>';
    
BaseController::$toolbar = $toolbar;
?>
<div class="row">
    <div class="col-lg-12">
        <div class="ibox">
            <div class="ibox-content">
                <div class="table-responsive">
                    <?php
        echo
        kartik\grid\GridView::widget([
            'id' => 'install-grid',
            'export' => false,
            'dataProvider' => $dataProvider,
            'resizableColumns' => false,
            'showPageSummary' => false,
            'headerRowOptions' => ['class' => 'kartik-sheet-style'],
            'filterRowOptions' => ['class' => 'kartik-sheet-style'],
            'responsive' => true,
            'hover' => true,
            'columns' => array(
                'name',
                'size:size',
                'create_time',
                'modified_time:relativeTime',
                [
                    'class' => 'kartik\grid\ActionColumn',
                    'template' => '{restore_action}',
                    'header' => 'Restore',
                    'buttons' => [
                        'restore_action' => function ($url, $model) {
                            return Html::a('<span class="btn btn-outline btn-warning dim btn-xs glyphicon glyphicon-repeat"></span>', $url, [
                                'title' => 'Restore','class'=>'restore'
                            ]);
                        }
                    ],
                    'urlCreator' => function ($action, $model, $key, $index) {
                        if ($action === 'restore_action') {
                            $url = Url::to(['restore', 'filename' => $model['name']]);
                            return $url;
                        }
                    }
                ],
                [
                    'class' => 'kartik\grid\ActionColumn',
                    'template' => '{delete_action}',
                    'header' => 'Delete',
                    'buttons' => [
                        'delete_action' => function ($url, $model) {
                            return Html::a('<span class="btn btn-outline btn-danger dim btn-xs glyphicon glyphicon-trash"></span>', $url, [
                                'title' => Yii::t('app', 'Delete Database'),'class'=>'delete',
                            ]);
                        }
                    ],
                    'urlCreator' => function ($action, $model, $key, $index) {
                        if ($action === 'delete_action') {
                            $url = Url::to(['delete', 'filename' => $model['name']]);
                            return $url;
                        }
                    }
                ],
            ),
        ]);
        ?>
                </div>
            </div>
        </div>
    </div>
</div>
