<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use yii\widgets\Pjax;
use yii\grid\GridView;
use common\models\ComissionPay;
use frontend\components\LabelComponent;
use frontend\components\AccessComponent;
use common\components\CurrencyComponent;
use common\components\DateComponent;
use frontend\components\ButtonComponent;
use app\components\BaseController;
use common\models\Product;


// Fungsi header dengan mengirimkan raw data PDF
// header("Content-type:application/pdf");
 
// Mendefinisikan nama file ekspor "hasil-export.xls"
//header("Content-Disposition: attachment; filename=laporan-kartu-stok.xls");
//readfile('original.pdf');
// header("Content-Disposition: attachment;filename=laporan-kartu-stok.pdf");
//header('Content-Type: application/pdf');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    
    </style>
    <style>
    
    </style>
</head>
<body>
<table border="0" style="width: 100%;">
    <tr>
        <td class="col-left">
            <div class="store-name">PREMIUM PARFUM INDONESIA</div>
        </td>
        <td class="col-right">
           <div class="store-name">LAPORAN KOMISI SALES</div>
           <div style="">Di Export Tanggal <?=date("d-m-Y H:i:s")?></div>
        </td>
    </tr>
</table>

<hr>
<div class="row">
    <div class="col-lg-12">
        <div class="ibox">
            <div class="ibox-content">
                <div class="table-responsive">
                    <?php $gridColumns = [
                        ['class' => 'yii\grid\SerialColumn'],
                        [
                            'attribute' => 'employee_name',
                            'label' => 'Sales',
                            'value' => function($model) {
                                return $model['employee_name'];
                            }
                        ],
                        [
                            'attribute' => 'comission_pay_periode',
                            'value' =>function($model){
                                return (new ComissionPay)->getPeriodeLabel($model['comission_pay_periode']);
                            },
                            // 'filter' => (new ComissionPay)->getPeriodeLabel(),
                        ],
                        [
                            'attribute' => 'comission_pay_value',
                            'label' => 'Jumlah Komisi',
                            'format' => 'raw',
                            'value' => function($model) {
                                $temp = (new ComissionPay)->getComissionPaid($model['id'], $model['comission_pay_periode'], $model['tahun']) + (new ComissionPay)->getComissionNotPay($model['id'], $model['comission_pay_periode'], $model['tahun']);;
                                if($model['comission_pay_exchange_rate'] > 1)
                                    return $temp !== 0 ? CurrencyComponent::formatMoney($temp) : "-";
                                else
                                    return $temp !== 0 ? CurrencyComponent::formatMoney($temp,0,',','.', Product::CURRENCY_DOLAR) : "-";
                            },
                            'filter' => false,
                        ],
                        [
                            'attribute' => 'comission_pay_value',
                            'label' => 'Jumlah Komisi Sudah Cair',
                            'format' => 'raw',
                            'value' => function($model) {
                                $temp = (new ComissionPay)->getComissionPaid($model['id'], $model['comission_pay_periode'], $model['tahun']);
                                if($model['comission_pay_exchange_rate'] > 1)
                                    return $temp !== 0 ? CurrencyComponent::formatMoney($temp) : "-";
                                else
                                    return $temp !== 0 ? CurrencyComponent::formatMoney($temp,0,',','.', Product::CURRENCY_DOLAR) : "-";
                            },
                            'filter' => false,
                        ],
                        [
                            'attribute' => 'comission_pay_value',
                            'label' => 'Jumlah Komisi Belum Cair',
                            'format' => 'raw',
                            'value' => function($model) {
                                $temp = (new ComissionPay)->getComissionNotPay($model['id'], $model['comission_pay_periode'], $model['tahun']);
                                if($model['comission_pay_exchange_rate'] > 1)
                                    return $temp !== 0 ? CurrencyComponent::formatMoney($temp) : "-";
                                else
                                    return $temp !== 0 ? CurrencyComponent::formatMoney($temp,0,',','.', Product::CURRENCY_DOLAR) : "-";
                            },
                            'filter' => false,
                        ],
                    ];?>
                    <?php Pjax::begin();?>
                    <?= GridView::widget([
                        'id' => 'sales-per-product-grid',
                        'dataProvider' => $dataProvider,
                        // 'filterModel' => $searchModel,
                        // 'showFooter' => true,
                        'formatter' => ['class' => 'yii\i18n\Formatter','nullDisplay' => ''],
                        // 'responsiveWrap' => true,
                        // 'condensed' => true,
                        // 'pjax' => true,
                        'columns' => $gridColumns,
                    ]); ?>
                    <?php Pjax::end();?>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
</html>