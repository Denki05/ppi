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


$this->title = BaseController::getCustomPageTitle(BaseController::$page_caption);
$toolbar[] = ButtonComponent::getExportPdf();
$toolbar[] = ButtonComponent::getExportExcel();

BaseController::$toolbar = $toolbar;

foreach(Yii::$app->session->getAllFlashes() as $key => $message)
    echo '<div class="alert alert-' . $key . '">' . $message . "</div>\n";
?>
<input id="baseUrl" type="hidden" value="<?=Url::base()?>/report/default">
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
                                'columns' => [
                                    ['class' => 'yii\grid\SerialColumn'],
                                    [
                                        'attribute' => 'employee_name',
                                        'label' => 'Sales',
                                        'value' => function($model) {
                                            return $model['employee_name'];
                                        }
                                    ],
                                    // [
                                    //     'attribute' => 'comission_pay_date',
                                    //     'value' => function($model) {
                                    //         return DateComponent::getHumanizedDate($model->comission_pay_date);
                                    //     },
                                    //     'filter' => Html::activeTextInput($searchModel, 'start_date', ['class' => 'form-control dropicker'])." hingga ".Html::activeTextInput($searchModel, 'end_date', ['class' => 'form-control dropicker'])
                                    // ],
                                    [
                                        'attribute' => 'comission_pay_periode',
                                        'value' =>function($model){
                                            return (new ComissionPay)->getPeriodeLabel($model['comission_pay_periode']);
                                        },
                                        'filter' => (new ComissionPay)->getPeriodeLabel(),
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

$(document).on("pjax:success", function(){
    const urlParams = new URLSearchParams(window.location.search);
    const myParam = urlParams.get('sort');
    var baseUrl = $('#baseUrl').val();
    // alert(urlParams);
    var urlexcel = baseUrl+'/exportexcel?'+urlParams;
    var urlpdf = baseUrl+'/exportpdf?'+urlParams;
    $("#excel_btn").attr("href", urlexcel);
    $('#pdf_btn').attr("href", urlpdf);
});    

$(document).ready(function(){
    $.pjax.defaults.timeout = 5000;
});
</script>
