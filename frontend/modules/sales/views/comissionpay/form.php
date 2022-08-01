<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
// use yii\widgets\ActiveForm;
use yii\bootstrap\ActiveForm;
use common\components\CurrencyComponent;
use frontend\components\LabelComponent;
use frontend\components\AccessComponent;
use app\components\BaseController;
use frontend\components\ButtonComponent;
use common\models\Bank;
use common\models\Product;
use common\models\Employee;
use common\models\Customer;
use common\models\SalesInvoice;
use common\models\SalesInvoiceItem;
use common\models\SalesPaymentDetail;

$this->title = BaseController::getCustomPageTitle(BaseController::$page_caption);
$toolbar[] = ButtonComponent::getBackButton();
$toolbar[] = ButtonComponent::getSaveButton();
	
BaseController::$toolbar = $toolbar;
	
foreach(Yii::$app->session->getAllFlashes() as $key => $message)
	echo '<div class="alert alert-' . $key . '">' . $message . "</div>\n";

?>
<input id="baseUrl" type="hidden" value="<?=Url::base()?>/sales/comissionpay">
<?php $form = ActiveForm::begin(['id' => 'application_form']); ?>
<?php echo Html::hiddenInput('saveandnew', '0', ['id' => 'hiddensaveandnew']);?>
<?php //echo $form->errorSummary(array($model));?>

<section class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-content">
                <div class="card-body padding-bottom-zero padding-top-zero"> 
                    <div class="row">
                        <div class="col-lg-4">
                            <?= $form->field($model, 'comission_pay_date')->textInput(['class'=>'form-control input-sm date-inputmask', "data-inputmask" =>"'alias': 'datetime','inputFormat': 'dd-mm-yyyy'"]) ?>
                        </div>
                        <div class="col-lg-4">
                            <?= $form->field($model, 'comission_pay_exchange_rate')->textInput(['class'=>'form-control input-sm angka']) ?>
                        </div>
                        <div class="col-lg-4">
                           <?= $form->field($model, 'comission_pay_periode')
                            ->dropDownList($model->getPeriodeLabel(), ['class' => 'form-control input-sm', 'prompt' => 'Pilih Periode']);
                            ?>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-4">
                            <?= $form->field($model, 'salesman_id')
                            ->dropDownList(isset($model->salesman_id) ? ArrayHelper::map(Employee::find()->where('id =:id', [':id' => $model->salesman_id])->all(), 'id', 'employee_name') : array(''), ['class' => 'form-control input-sm select2', 'prompt' => 'Pilih Sales']);
                            ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<section class="row section-detail">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-content">
                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="row-template" style="display: none">
                                <table class="table-template">
                                    <tr id="row-{index}" class="row-item">
                                      <td><span class="invoice-code-label"></span></td>
                                      <td><span class="customer-name-label"></span></td>
                                      <td><span class="invoice-date-label"></span></td>
                                      <td>
                                        <span class="invoice-comission-value-label"></span>
                                        <input type="hidden" name="comission_value" class="invoice-comission-value">
                                      </td>
                                      <td><span class="payment-date-label"></span></td>
                                      <td>
                                          <?= Html::textInput('item[{index}][comission_percent]', '', ['class' => 'form-control input-sm angka comission-percent'])?>
                                      </td>
                                      <td class="text-right">
                                        <span class="subtotal-label" ></span>
                                            <input type="hidden" name="item[{index}][comission_amount]" class="subtotal">
                                            <input type="hidden" name="item[{index}][invoice_id]" class="invoice-id">
                                      </td>
                                    </tr>
                                </table>
                            </div>
                            <div class="table-responsive">
                                <table class="table table-striped table-bordered table-item">
                                    <thead>
                                        <tr>
                                            <th style="width: 10%;"><center> <?= (new SalesInvoice)->getAttributeLabel('invoice_code')?></center></th>
                                            <th style="width: 20%;"><center><?= (new SalesInvoice)->getAttributeLabel('customer_name')?></center></th>
                                            <th style="width: 10%;"><center><?= (new SalesInvoice)->getAttributeLabel('invoice_date')?></center></th>
                                            <th style="width: 10%;"><center><?= (new SalesInvoice)->getAttributeLabel('invoice_comission_value')?></center></th>
                                            <th style="width: 20%;"><center><?= (new SalesInvoice)->getAttributeLabel('payment_date')?></center></th>
                                            <th style="width: 10%;"><center>Komisi Cair (%)</center></th>
                                            <th style="width: 20%;"><center>Subtotal</center></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if($mode == 'update'): ?>
                                                <?php $i = 1; ?>
                                            <?php foreach ($model->comissionPayDetails as $item):?>
                                                <tr id="row-<?= $i ?>" class="row-item">
                                                  <td><span class="invoice-code-label"><?= $item->invoice->invoice_code?></span></td>
                                                  <td><span class="customer-name-label"><?= $item->invoice->customer->customerName?></span></td>
                                                  <td><span class="invoice-date-label"><?= date("d-m-Y", strtotime($item->invoice->invoice_date))?></span></td>
                                                  <td>
                                                    <span class="invoice-comission-value-label"><?= CurrencyComponent::formatMoney($item->invoice->invoice_comission_value,0,',','.', Product::CURRENCY_DOLAR)?></span>
                                                    <input type="hidden" name="comission_value" class="invoice-comission-value" value="<?= $item->invoice->invoice_comission_value?>">
                                                  </td>
                                                  <td><span class="payment-date-label"><?= date("d-m-Y", strtotime($item->invoice->getLastPayment()))?></span></td>
                                                  <td>
                                                      <?= Html::textInput('item['.$i.'][comission_percent]', $item->comission_pay_detail_percent, ['class' => 'form-control input-sm angka comission-percent'])?>
                                                  </td>
                                                  <td class="text-right">
                                                    <span class="subtotal-label" ><?= CurrencyComponent::formatMoney($item->comission_pay_detail_amount)?></span>
                                                        <input type="hidden" name="item[<?= $i ?>][comission_amount]" class="subtotal" value="<?= $item->comission_pay_detail_amount?>">
                                                        <input type="hidden" name="item[<?= $i ?>][invoice_id]" class="invoice-id" value="<?= $item->invoice_id?>">
                                                  </td>
                                                </tr>
                                                <?php $i++; ?>
                                            <?php endforeach; ?>
                                        <?php endif; ?>
                                        <tr class="row-footer-subtotal">
                                            <td colspan="6" class="text-right"><strong>Total</strong></td>
                                            <td class="text-right">
                                                <span class="total-label"></span>
                                                <input type="hidden" name="ComissionPay[comission_pay_value]" class="total">
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<?php ActiveForm::end(); ?>
<script>

function addRow(data)
{
    var index = 0;
    if (typeof $(".table-item .row-item").last().attr("id") != 'undefined')
        index = parseInt($(".table-item .row-item").last().attr("id").split("-")[1]) + 1;
        
    var clone = $(".table-template .row-item").clone().attr("id", "row-"+index);
    
    clone.html(function(i, oldTr) {return oldTr.replace(/\{index}/g, index);});
    
    $(".row-footer-subtotal").before(clone);

    var tr = $(".table-item").find("#row-"+index);
    tr.find(".invoice-code-label").html(data.invoice_code);
    tr.find(".invoice-id").val(data.id);
    tr.find(".customer-name-label").html(data.customer_name);
    tr.find(".invoice-date-label").html(data.invoice_date);
    tr.find(".invoice-comission-value-label").html('$'+format_usd(data.invoice_comission_value));
    tr.find(".invoice-comission-value").val(data.invoice_comission_value);
    tr.find(".payment-date-label").html(data.payment_date);   
    tr.find(".subtotal").val(0);
    tr.find(".subtotal-label").html("Rp"+number_format(0, 0, ',', '.'));
    tr.find(".comission-percent").val(0);
   
   
    total();
}

function toggleSecstionDetail(){
    if($('#comissionpay-salesman_id').val() != ""){
        $('.section-detail').show();
    }
    else{
        $('.section-detail').hide();
    }
}

function total(){
    var n = $('.table-item .subtotal').length;
    
    var hsl = $(".table-item .subtotal");
    var total = 0;
    for(var i = 0; i < n; i++){
        total = total + Number($(hsl[i]).val());
        // alert(total);
    }
    // alert(total);
    
    $('.total').val(total);
    $('.total-label').html("Rp"+number_format(total, 0, ',', '.'));  
}

function comission(percent, comissionvalue, kurs){
    var tmp = parseFloat(comissionvalue) * parseFloat(kurs);
    var subtotal = (parseFloat(tmp) * parseFloat(percent)) / 100;
    return subtotal;
}

function totalcomission(kurs){
    var n = $('.table-item .subtotal').length;
    
    var subtotal = $(".table-item .subtotal");
    var subtotalLabel = $(".table-item .subtotal-label");
    var comissionvalue = $(".table-item .invoice-comission-value");
    var comissionpercent = $(".table-item .comission-percent");


    
    for(var i = 0; i < n; i++){
        var total = 0;
        
        total = comission($(comissionpercent[i]).val(), $(comissionvalue[i]).val(), kurs);

        $(subtotal[i]).val(total);
        $(subtotalLabel[i]).html("Rp"+number_format(total, 0, ',', '.'));
    }
}

$(document).on('input', '.comission-percent', function(){

    var percent = 0;
    if($(this).val() !="")
        percent = $(this).val();

    var tr = $(this).closest('tr');
    var amount = tr.find(".invoice-comission-value").val();

    var subtotal = comission(percent, amount,  $("#comissionpay-comission_pay_exchange_rate").val());
    tr.find(".subtotal").val(subtotal);
    tr.find(".subtotal-label").html("Rp"+number_format(subtotal, 0, ',', '.'));

    total();
});



$(document).on('change', '#comissionpay-comission_pay_exchange_rate', function(){
    totalcomission($(this).val());
    total();
});

$(document).on('change', '#comissionpay-comission_pay_periode', function(){
    var periode = $(this).val();
    var url = $('#baseUrl').val();

    $.get(url+'/getsalesman',{periode:periode},function(response){
        let data = $.parseJSON(response);
        
        $('#comissionpay-salesman_id').empty();
        $('#comissionpay-salesman_id').append(
            '<option value="">Pilih Sales</option>'
        );
        $.each(data, function(key, value){
            $('#comissionpay-salesman_id').append(
                    '<option value='+data[key].id+'>'+data[key].employee_name+'</option>'
              );
        });

    });

});


$(document).on('change', '#comissionpay-salesman_id', function(){
    toggleSecstionDetail();
    var id = $(this).val();
    var url = $('#baseUrl').val();
    var periode = $('#comissionpay-comission_pay_periode').val();

    $.get(url+'/getinvoice',{id:id, periode:periode},function(response){
        let data = $.parseJSON(response);
        
        $('.table-item .row-item').remove();
        
        for(var i=0; i<data.length;i++){
            addRow(data[i]);
        }
    });

});

$(document).ready(function(){
    
    toggleSecstionDetail();
    total();
});

</script>