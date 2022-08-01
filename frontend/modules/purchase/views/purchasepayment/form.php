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
use common\models\Customer;
use common\models\PurchaseOrder;
use common\models\PurchaseOrderItem;
use common\models\PurchasePaymentDetail;

$this->title = BaseController::getCustomPageTitle(BaseController::$page_caption);
$toolbar[] = ButtonComponent::getBackButton();
$toolbar[] = ButtonComponent::getSaveButton();
	
BaseController::$toolbar = $toolbar;
	
foreach(Yii::$app->session->getAllFlashes() as $key => $message)
	echo '<div class="alert alert-' . $key . '">' . $message . "</div>\n";

?>
<input id="baseUrl" type="hidden" value="<?=Url::base()?>/purchase/purchasepayment">
<?php $form = ActiveForm::begin(['id' => 'application_form']); ?>
<?php echo Html::hiddenInput('saveandnew', '0', ['id' => 'hiddensaveandnew']);?>
<?php //echo $form->errorSummary(array($model));?>

<section class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-content">
                <div class="card-body padding-bottom-zero padding-top-zero"> 
                    <div class="row">
                        <div class="col-lg-6">
                            <?= $form->field($model, 'purchase_payment_date')->textInput(['class'=>'form-control input-sm date-inputmask', "data-inputmask" =>"'alias': 'datetime','inputFormat': 'dd-mm-yyyy'"]) ?>
                        </div>
                        <div class="col-lg-6">
                            <?php if($mode != 'update'): ?>
                            <?= $form->field($model, 'purchase_order_id')
                                ->dropDownList(ArrayHelper::map(PurchaseOrder::find()->where('purchase_order_status != :s', [':s' => 'close'])
                                    ->andWhere('is_deleted=:is', [':is' => 0])->orderBy(['purchase_order_code' => SORT_ASC])->all(), 'id', 'purchase_order_code')
                                ,['class' => 'form-control input-sm select2', 'prompt' => 'Pilih Nota']);
                            ?>
                        <?php else: ?>
                             <?= $form->field($model, 'purchase_order_id')
                                ->dropDownList(ArrayHelper::map(PurchaseOrder::find()->where('id=:id', [':id' => $model->purchase_order_id])->all(), 'id', 'purchase_order_code')
                                ,['class' => 'form-control input-sm select2']);
                            ?>
                        <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<section class="row payment-session">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">Detail Nota</h4>
                <a class="heading-elements-toggle"><i class="la la-ellipsis-v font-medium-3"></i></a>
                <div class="heading-elements">
                    <ul class="list-inline mb-0">
                        <li><a data-action="collapse"><i class="ft-minus"></i></a></li>
                    </ul>
                </div>
            </div>
            <div class="card-content">
                <div class="card-body padding-top-zero padding-bottom-zero">
                    <div class="row">
                        <div class="col-lg-4">
                            <div class="row mb-1">
                                <div class="col-lg-6"><b><?=(new PurchaseOrder)->getAttributeLabel('purchase_order_code')?></b></div>
                                <div class="col-lg-1">:</div>
                                <div class="col-lg-5"><span class="detail-purchase-code"><?=isset($model->purchaseOrder->purchase_order_code) ? $model->purchaseOrder->purchase_order_code : '' ?></span></div>
                            </div>  
                            <div class="row mb-1">
                                <div class="col-lg-6"><b><?=(new PurchaseOrder)->getAttributeLabel('purchase_order_date')?></b></div>
                                <div class="col-lg-1">:</div>
                                <div class="col-lg-5"><span class="detail-purchase-date"><?=isset($model->purchaseOrder->purchase_order_date) ? date("d-m-Y", strtotime($model->purchaseOrder->purchase_order_date)) : '' ?></span></div>
                            </div>
                        </div>
                        <div class="col-lg-3">
                            <div class="row mb-1">
                                <div class="col-lg-4"><b><?=(new PurchaseOrder)->getAttributeLabel('supplier_id')?></b></div>
                                <div class="col-lg-1">:</div>
                                <div class="col-lg-5"><span class="detail-supplier"><?=isset($model->supplier->supplier_name) ? $model->supplier->supplier_name : ''?></span></div>
                            </div>
                            <div class="row mb-1">
                                <div class="col-lg-4"><b><?=(new PurchaseOrder)->getAttributeLabel('purchase_order_notes')?></b></div>
                                <div class="col-lg-1">:</div>
                                <div class="col-lg-5"><span class="detail-purchase-notes"><?=isset($model->purchaseOrder->purchase_order_notes) ? $model->purchaseOrder->purchase_order_notes : ''?></span></div>
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="row mb-1">
                                <div class="col-lg-3"><b><?=(new PurchaseOrder)->getAttributeLabel('purchase_order_status')?></b></div>
                                <div class="col-lg-1">:</div>
                                <div class="col-lg-5"><span class="detail-purchase-status"><?=isset($model->purchase_order_status) ? $model->purchase_order_status : ''?></span></div>
                            </div>
                        </div>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-lg-12">       
                            <div class="table-responsive">
                                <table class="table table-striped table-bordered table-item">
                                    <thead>
                                        <tr>
                                           <th style="width: 20%"><center><?= (new PurchaseOrderItem)->getAttributeLabel('product_id')?></center></th>
                                            <th style="width: 10%"><center><?= (new PurchaseOrderItem)->getAttributeLabel('purchase_order_item_qty')?></center></th>
                                            <th style="width: 15%;"><center><?= (new PurchaseOrderItem)->getAttributeLabel('purchase_order_item_disc_amount')?></center></th>
                                            <th style="width: 15%;"><center><?= (new PurchaseOrderItem)->getAttributeLabel('purchase_order_item_disc_percent')?></center></th>
                                            <th style="width: 20%"><center><?= (new PurchaseOrderItem)->getAttributeLabel('purchase_order_item_price')?></center></th>
                                            <th style="width: 20%;"><center><?= (new PurchaseOrderItem)->getAttributeLabel('purchase_order_item_row_total')?></center></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if($mode == 'update' && isset($model->purchaseOrder->purchaseOrderItems)): ?>
                                        <?php foreach($model->purchaseOrder->purchaseOrderItems as $item):?>
                                        <tr class="row-item">
                                            <td>
                                                <center><span class="product-name"><?= $item->product->product_name ?></span></center>
                                            </td>
                                            <td class="text-right">
                                                <span><?= $item->purchase_order_item_qty ?></span>
                                            </td>
                                            <td class="text-right">
                                                <?= CurrencyComponent::formatMoney($item->purchase_order_item_disc_amount,0,',','.', Product::CURRENCY_DOLAR) ?>
                                            </td>
                                            <td class="text-right">
                                                <?= $item->purchase_order_item_disc_percent ?> %
                                            </td>
                                            <td class="text-right">
                                                <?= CurrencyComponent::formatMoney($item->purchase_order_item_price,0,',','.', Product::CURRENCY_DOLAR) ?>
                                            </td>
                                            <td class="text-right">
                                                <?= CurrencyComponent::formatMoney($item->purchase_order_item_row_total,0,',','.', Product::CURRENCY_DOLAR) ?>
                                            </td>
                                        </tr>
                                        <?php endforeach;?>
                                        <?php endif; ?>
                                    <tr>
                                        <td colspan="5" class="text-right"><span><b><?= (new PurchaseOrder)->getAttributeLabel('purchase_order_subtotal')?></b></span></td>
                                        <td class="text-right">
                                            <strong><span class="detail-purchase-subtotal"><?= isset($model->purchaseOrder->purchase_order_subtotal) ? CurrencyComponent::formatMoney($model->purchaseOrder->purchase_order_subtotal,0,',','.', Product::CURRENCY_DOLAR) : 0 ?></span></strong>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan="5" class="text-right"><span><b><?= (new PurchaseOrder)->getAttributeLabel('purchase_order_disc_amount')?></b></span></td>
                                        <td class="text-right">
                                             <span class="detail-purchase-disc-amount"><?= isset($model->purchaseOrder->purchase_order_disc_amount) ? CurrencyComponent::formatMoney($model->purchaseOrder->purchase_order_disc_amount,0,',','.', Product::CURRENCY_DOLAR) : 0 ?> </span><br>
                                             <span class="detail-purchase-disc-percent"><?= isset($model->purchaseOrder->purchase_order_disc_percent) ? $model->purchaseOrder->purchase_order_disc_percent : 0?> %</span>

                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan="5" class="text-right"><span><b><?= (new PurchaseOrder)->getAttributeLabel('purchase_order_grand_total')?></b></span></td>
                                        <td class="text-right">
                                            <strong><span class="detail-purchase-grand-total"><?= isset($model->purchaseOrder->purchase_order_grand_total) ? CurrencyComponent::formatMoney($model->purchaseOrder->purchase_order_grand_total,0,',','.', Product::CURRENCY_DOLAR) : 0 ?></span></strong>
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
<section class="row payment-session">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-content">
                <div class="card-body padding-top-zero">
                   <div class="row">
                        <div class="grand-header">
                            <center>
                                <span><b>Total Nota</b></span>
                                <input type="hidden" class="grandtotal" value="<?= isset($model->purchaseOrder->purchase_order_grand_total) ? $model->purchaseOrder->purchase_order_grand_total : 0; ?>" >
                                <h2><b><p class="grandtotal-label">$<?= isset($model->purchaseOrder->purchase_order_grand_total) ? $model->purchaseOrder->purchase_order_grand_total : 0;?></p></b></h2>
                            </center>
                        </div>
                        <div class="paid-header">
                            <center>
                                <span><b>Total Terbayar</b></span>
                                <input type="hidden" class="paid" value="<?= isset($model->purchaseOrder) ? $model->getPaidAmount($model->purchase_order_id) : 0 ?>">
                                <h2><b><span class="paid-label"><?= isset($model->purchaseOrder) ? CurrencyComponent::formatMoney($model->getPaidAmount($model->purchase_order_id),0,',','.', Product::CURRENCY_DOLAR) : '$0';?></span></b></h2>
                            </center>
                        </div>
                        <div class="mustpay-header">
                            <center>
                                <span><b>Total Harus Dibayar</b></span>
                                <input type="hidden" class="mustpay" value="<?= isset($model->purchaseOrder->purchase_order_grand_total) ? $model->purchaseOrder->purchase_order_grand_total : 0;?>">
                                <h2><b><span class="mustpay-label">$<?= isset($model->purchaseOrder->purchase_order_grand_total) ? $model->purchaseOrder->purchase_order_grand_total : '$0';?></span></b></h2>
                            </center>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="row-template" style="display: none">
                                <table class="table-template">
                                    <tr id="row-{index}" class="row-item">
                                        <td>
                                            <?= Html::dropDownList('item[{index}][purchase_payment_detail_method]', '', (new PurchasePaymentDetail)->getPayMethodLabel(), ['class' => 'form-control input-sm payment-detail-method']) ?>
                                            <strong><span class="bank-label"><?= (new PurchasePaymentDetail)->getAttributeLabel('bank_id')?></span></strong>
                                            <?= Html::dropDownList('item[{index}][bank_id]', '', ArrayHelper::map(Bank::find()->where('is_deleted=:is', [':is' => 0])->orderBy(['bank_acc_name' => SORT_ASC])->all(), 'id', 'bankName'), ['class' => 'form-control input-sm bank-id', 'prompt' => 'Pilih Bank']) ?>
                                            <?=Html::textInput('item[{index}][purchase_payment_detail_bank_acc_name]', '', array('class' => 'form-control bank-acc-name input-sm'));?>
                                            <?=Html::textInput('item[{index}][purchase_payment_detail_bank_acc_number]', '', array('class' => 'form-control bank-acc-number input-sm'));?>
                                            <strong><span class="credit-label"><?= (new PurchasePaymentDetail)->getAttributeLabel('purchase_payment_detail_creditcard_number')?></span></strong>
                                            <?=Html::textInput('item[{index}][purchase_payment_detail_creditcard_number]', '', array('class' => 'form-control creditcard-number input-sm'));?>
                                            <strong><span class="debit-label"><?= (new PurchasePaymentDetail)->getAttributeLabel('purchase_payment_detail_debitcard_number')?></span></strong>
                                            <?=Html::textInput('item[{index}][purchase_payment_detail_debitcard_number]', '', array('class' => 'form-control debitcard-number input-sm'));?>
                                        </td>
                                        <td>
                                            <?=Html::textInput('item[{index}][purchase_payment_detail_amount]', '', array('class' => 'form-control payment-detail-amount angka input-sm'));?>
                                        </td>
                                        <td><a href="#" class="btn btn-outline-danger btn-sm btn-remove"><i class="la la-trash"></i></a></td>
                                    </tr>
                                </table>
                            </div>
                            <div class="table-responsive">
                                <table class="table table-striped table-bordered table-item-payment">
                                    <thead>
                                        <tr>
                                            <th style="width: 40%;"><?= (new PurchasePaymentDetail)->getAttributeLabel('purchase_payment_detail_method')?></th>
                                            <th style="width: 40%;"><?= (new PurchasePaymentDetail)->getAttributeLabel('purchase_payment_detail_amount')?></th>
                                            <th style="width: 10%;"></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    <?php if (empty($items)):?>
                                    <tr class="row-item" id="row-0">
                                        <td>
                                            <?= Html::dropDownList('item[0][purchase_payment_detail_method]', '', (new PurchasePaymentDetail)->getPayMethodLabel(), ['class' => 'form-control input-sm payment-detail-method']) ?>
                                            <strong><span class="bank-label"><?= (new PurchasePaymentDetail)->getAttributeLabel('bank_id')?></span></strong>
                                            <?= Html::dropDownList('item[0][bank_id]', '', ArrayHelper::map(Bank::find()->where('is_deleted=:is', [':is' => 0])->orderBy(['bank_acc_name' => SORT_ASC])->all(), 'id', 'bankName'), ['class' => 'form-control input-sm bank-id', 'prompt' => 'Pilih Bank']) ?>
                                            <?=Html::textInput('item[0][purchase_payment_detail_bank_acc_name]', '', array('class' => 'form-control bank-acc-name input-sm'));?>
                                            <?=Html::textInput('item[0][purchase_payment_detail_bank_acc_number]', '', array('class' => 'form-control bank-acc-number input-sm'));?>
                                            <strong><span class="credit-label"><?= (new PurchasePaymentDetail)->getAttributeLabel('purchase_payment_detail_creditcard_number')?></span></strong>
                                            <?=Html::textInput('item[0][purchase_payment_detail_creditcard_number]', '', array('class' => 'form-control creditcard-number input-sm'));?>
                                            <strong><span class="debit-label"><?= (new PurchasePaymentDetail)->getAttributeLabel('purchase_payment_detail_debitcard_number')?></span></strong>
                                            <?=Html::textInput('item[0][purchase_payment_detail_debitcard_number]', '', array('class' => 'form-control debitcard-number input-sm'));?>
                                        </td>
                                        <td>
                                            <?=Html::textInput('item[0][purchase_payment_detail_amount]', '', array('class' => 'form-control payment-detail-amount angka input-sm'));?>
                                        </td>
                                        <td></td>
                                    </tr>
                                    <?php endif; ?>
                                    <?php if (!empty($items)):?>
                                        <?php foreach($items as $i => $item):?>
                                            <?php if ($i !== "{index}"):?>
                                        <tr id="row-<?=$i?>" class="row-item">
                                            <td>
                                                <?= Html::dropDownList('item['.$i.'][purchase_payment_detail_method]', $item['purchase_payment_detail_method'], (new PurchasePaymentDetail)->getPayMethodLabel(), ['class' => 'form-control input-sm payment-detail-method']) ?>
                                                <strong><span class="bank-label"><?= (new PurchasePaymentDetail)->getAttributeLabel('bank_id')?></span></strong>
                                                <?= Html::dropDownList('item['.$i.'][bank_id]', $item['bank_id'], ArrayHelper::map(Bank::find()->where('is_deleted=:is', [':is' => 0])->orderBy(['bank_acc_name' => SORT_ASC])->all(), 'id', 'bankName'), ['class' => 'form-control input-sm bank-id', 'prompt' => 'Pilih Bank']) ?>
                                                <?=Html::textInput('item['.$i.'][purchase_payment_detail_bank_acc_name]', $item['purchase_payment_detail_bank_acc_name'], array('class' => 'form-control bank-acc-name input-sm'));?>
                                                <?=Html::textInput('item['.$i.'][purchase_payment_detail_bank_acc_number]', $item['purchase_payment_detail_bank_acc_number'], array('class' => 'form-control bank-acc-number input-sm'));?>
                                                <strong><span class="credit-label"><?= (new PurchasePaymentDetail)->getAttributeLabel('purchase_payment_detail_creditcard_number')?></span></strong>
                                                <?=Html::textInput('item['.$i.'][purchase_payment_detail_creditcard_number]', $item['purchase_payment_detail_creditcard_number'], array('class' => 'form-control creditcard-number input-sm'));?>
                                                <strong><span class="debit-label"><?= (new PurchasePaymentDetail)->getAttributeLabel('purchase_payment_detail_debitcard_number')?></span></strong>
                                                <?=Html::textInput('item['.$i.'][purchase_payment_detail_debitcard_number]', $item['purchase_payment_detail_debitcard_number'], array('class' => 'form-control debitcard-number input-sm'));?>
                                            </td>
                                            <td>
                                                <?=Html::textInput('item['.$i.'][purchase_payment_detail_amount]', $item['purchase_payment_detail_amount'], array('class' => 'form-control payment-detail-amount angka input-sm'));?>
                                            </td>
                                            <td><a href="#" class="btn btn-outline-danger btn-sm btn-remove"><i class="la la-trash"></i></a></td>
                                        </tr>
                                            <?php endif;?>
                                        <?php endforeach;?>
                                    <?php endif;?>
                                    <tr class="row-footer-button-add">
                                        <td colspan="2">
                                            <button href="javascript:;" onclick="" type="button" class="btn btn-outline-info btn-sm btn-add-row"><i class="la la-plus-circle"></i> Tambah baris</button>
                                        </td>
                                        <td></td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-12">
                            <center>
                                <span><b>Total Bayar</b></span>
                                <input type="hidden" name="PurchasePayment[purchase_payment_total_amount]" class="payment-total-amount">
                                <h2><b><span class="payment-total-amount-label">$0</span></b></h2>
                            </center>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-12">
                            <center>
                                <span><b>Kembalian</b></span>
                                <input type="hidden" class="change" value="">
                                <h2><b><span class="change-label">$0</span></b></h2>
                            </center>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-12">
                            <center>
                                <span><b>Sisa Bayar</b></span>
                                <input type="hidden" class="outstanding" value="">
                                <h2><b><span class="outstanding-label">$0</span></b></h2>
                            </center>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<?php ActiveForm::end(); ?>
<script>
function addRow()
{
    var index = 0;
    if (typeof $(".table-item-payment .row-item").last().attr("id") != 'undefined')
        index = parseInt($(".table-item-payment .row-item").last().attr("id").split("-")[1]) + 1;
        
    var clone = $(".table-template .row-item").clone().attr("id", "row-"+index);
    
    clone.html(function(i, oldTr) {return oldTr.replace(/\{index}/g, index);});
    
    $(".row-footer-button-add").before(clone);
}

function mustpayAmount(){
    var grandtotal = $('.grandtotal').val();
    var paid = $('.paid').val();

    var mustpay = parseFloat(grandtotal) - parseFloat(paid);
    $('.mustpay').val(mustpay);
    $('.mustpay-label').html('$'+format_usd(mustpay));
}

function togglePayment(){
    if($('#purchasepayment-purchase_order_id').val() == '' || $('#salespayment-invoice_id').val() == 0)
        $('.payment-session').hide();
    else
        $('.payment-session').show();
}

function bankFieldHide(){
    $('.bank-acc-name').hide();
    $('.bank-acc-number').hide();
}

function methodFieldHide(){
    $('.bank-label').hide();
    $('.bank-id').hide();
    $('.credit-label').hide();
    $('.creditcard-number').hide();
    $('.debit-label').hide();
    $('.debitcard-number').hide();
}

function total(){
    var n = $('.table-item-payment .payment-detail-amount').length;
    var mustpay = $('.mustpay').val();
    var hsl = $(".table-item-payment .payment-detail-amount");
    var total = 0;
    for(var i = 0; i < n; i++){
        total = total + Number($(hsl[i]).val());
        // alert(total);
    }
    // alert(total);
    
    $('.payment-total-amount').val(total);
    $('.payment-total-amount-label').html('$'+format_usd(total));

    var change = parseFloat(total) - parseFloat(mustpay);
    $('.change').val(change);
    $('.change-label').html("$"+format_usd(change));
    var outstanding = parseFloat(mustpay) - parseFloat(total);
    $('.outstanding').val(outstanding);
    $('.outstanding-label').html('$'+format_usd(outstanding));
    // var sisa = 0;
    // if(total <= $('#mustpay').val()){
    //     sisa = $('#mustpay').val() - total;
    // }else{
    //     $('#totalbayar').val($('#mustpay').val());
    //     sisa = $('#mustpay').val() - total;
    // }
    // $('#sisa-bayar').html("Rp"+number_format(sisa, 0, ',', '.'));
}

function checkHeader(){
    if($('.paid').val() != 0){
        $('.paid-header').show();
        $('.paid-header').addClass('col-lg-4');
        $('.grand-header').removeClass('col-lg-6').addClass('col-lg-4');
        $('.mustpay-header').removeClass('col-lg-6').addClass('col-lg-4');
    }
    else{
        $('.paid-header').hide();
        $('.grand-header').addClass('col-lg-6');
        $('.mustpay-header').addClass('col-lg-6');
    }
    
}


$(document).on('click', '.btn-remove', function(e){

    var tr = $(this).closest('tr');
    tr.remove();
    total();
    e.preventDefault();

});

$(document).on('change', '.payment-detail-method', function(e){
    var index = $(this).val();
    var tr = $(this).closest('tr');
    
    if(index == 'banktransfer'){
        tr.find(".bank-label").show();
        tr.find(".bank-id").show();
        tr.find('.credit-label').hide();
        tr.find('.creditcard-number').hide();
        tr.find('.debit-label').hide();
        tr.find('.debitcard-number').hide();
    }
    else if(index == 'creditcardbca' || index == 'creditcardmandiri' || index == 'creditcardbri' || index == 'creditcardbni'){
        tr.find(".bank-label").hide();
        tr.find(".bank-id").hide();
        tr.find('.credit-label').show();
        tr.find('.creditcard-number').show();
        tr.find('.debit-label').hide();
        tr.find('.debitcard-number').hide();
    }
    else if(index == 'debitbca' || index == 'debitmandiri' || index == 'debitbri' || index == 'debitbni'){
        tr.find(".bank-label").hide();
        tr.find(".bank-id").hide();
        tr.find('.credit-label').hide();
        tr.find('.creditcard-number').hide();
        tr.find('.debit-label').show();
        tr.find('.debitcard-number').show();
    }
    else{
        tr.find(".bank-label").hide();
        tr.find(".bank-id").hide();
        tr.find('.credit-label').hide();
        tr.find('.creditcard-number').hide();
        tr.find('.debit-label').hide();
        tr.find('.debitcard-number').hide();
    }

});

$(document).on("change", ".bank-id", function(e){
    var id = $(this).val();
    var tr = $(this).closest('tr');
    var url = $('#baseUrl').val();
    $number = tr.find('.bank-acc-number');
    $name = tr.find('.bank-acc-name');
    // console.log(id);
    $.get(url+'/getitem',{id:$(this).val()},function(response){
        let data = $.parseJSON(response);
        // console.log(data.bank_acc_name); 
        $number.val(data.bank_acc_number);
        $name.val(data.bank_acc_name);
    });
});

$(document).on('input', '.payment-detail-amount', function(e){
    total();
});

$(document).on('change', '#purchasepayment-purchase_order_id', function(){
    var id = $(this).val();
    var url = $('#baseUrl').val();
    if(id != 0){
        $.get(url+'/getdetailpurchase',{id:id},function(response){
            let data = $.parseJSON(response);
            $('.detail-purchase-code').html(data.purchase_order_code);
            $('.detail-purchase-date').html(data.purchase_order_date);
            $('.detail-purchase-subtotal').html('$'+format_usd(data.purchase_order_subtotal));
            $('.detail-supplier').html(data.supplier_name);
            $('.detail-purchase-disc-amount').html('$'+format_usd(data.purchase_order_disc_amount));
            $('.detail-purchase-disc-percent').html(data.purchase_order_disc_percent+' %');
            $('.detail-purchase-grand-total').html('$'+format_usd(data.purchase_order_grand_total));
            $('.detail-purchase-status').html(data.purchase_order_status);
            $('.detail-purchase-notes').html(data.purchase_order_notes);
            $('.grandtotal').val(data.purchase_order_grand_total);
            $('.grandtotal-label').html('$'+format_usd(data.purchase_order_grand_total));
            // $('.prevInvoice').val(data.customer_debt_amount);
            // $('.prevInvoice-label').html('$'+format_usd(data.customer_debt_amount));
            $('.paid').val(data.paid_amount);
            $('.paid-label').html('$'+format_usd(data.paid_amount));

            mustpayAmount();
            itemDetail();
            total();
            checkHeader();
            
        });
    }
    togglePayment();
});

function itemDetail()
{
    var id = $("#purchasepayment-purchase_order_id").val();
    var url = $('#baseUrl').val();
    var item = "";

    $.get(url+'/getitempurchase',{id:$("#purchasepayment-purchase_order_id").val()},function(response){
        let data = $.parseJSON(response);
        
        $('.table-item .row-detail-invoice').remove();
        
        for(var i=0; i<data.length;i++){
            item += '<tr class="row-detail-invoice">'+
            // '<td>'+data[i].invoice_product_code+'</td>'+
            '<td><center>'+data[i].purchase_product_name+'</center></td>'+
            '<td class="text-right">'+data[i].purchase_order_item_qty+'</td>'+
            '<td class="text-right">'+"$"+format_usd(data[i].purchase_order_item_disc_amount)+'</td>'+
            '<td class="text-right">'+data[i].purchase_order_item_disc_percent+'</td>'+
            '<td class="text-right">'+"$"+format_usd(data[i].purchase_order_item_price)+'</td>'+
            '<td class="text-right">'+"$"+format_usd(data[i].purchase_order_item_row_total)+'</td>'+
        '</tr>';
        }
        $('.table-item').prepend(item);
    });
}

$(document).ready(function(){
    bankFieldHide();
    methodFieldHide();
    total();
    togglePayment();

    $(".btn-add-row").click(function(){
        addRow();
    });

    checkHeader();
});

</script>