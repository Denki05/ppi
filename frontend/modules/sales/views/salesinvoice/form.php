<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
// use yii\widgets\ActiveForm;
use yii\bootstrap\ActiveForm;

use frontend\components\LabelComponent;
use frontend\components\AccessComponent;
use app\components\BaseController;
use frontend\components\ButtonComponent;
use common\components\CurrencyComponent;
use common\models\Employee;
use common\models\Customer;
use common\models\Product;
use common\models\Brand;
use common\models\Category;
use common\models\ComissionType;
use common\models\SalesInvoiceItem;
use common\models\SalesInvoice;
use common\models\Packaging;

$this->title = BaseController::getCustomPageTitle(BaseController::$page_caption);
$toolbar[] = ButtonComponent::getBackButton();
$toolbar[] = ButtonComponent::getSaveButton();
	
BaseController::$toolbar = $toolbar;
	
foreach(Yii::$app->session->getAllFlashes() as $key => $message)
	echo '<div class="alert alert-' . $key . '">' . $message . "</div>\n";

?>
<input id="baseUrl" type="hidden" value="<?=Url::base()?>/sales/salesinvoice">
<?php $form = ActiveForm::begin(['id' => 'application_form']); ?>
<?php echo Html::hiddenInput('saveandnew', '0', ['id' => 'hiddensaveandnew']);?>
<?php //echo $form->errorSummary(array($model));?>

<section class="row">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-content">
                <div class="card-body padding-bottom-zero padding-top-zero">
                    <div class="row">
                        <div class="col-lg-6">
                            <?= $form->field($model, 'invoice_date')->textInput(['class'=>'form-control input-sm date-inputmask', "data-inputmask" =>"'alias': 'datetime','inputFormat': 'dd-mm-yyyy'"]) ?>
                        </div>
                        <div class="col-lg-6">
                            <?= $form->field($model, 'salesman_id')
                                ->dropDownList(ArrayHelper::map(employee::find()->all(),'id','employee_name' ), ['class' => 'form-control input-sm select2', 'prompt' => 'pilih sales']);
                            ?>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-6">
                            <?= $form->field($model, 'comission_type_id')
                                ->dropDownList(ArrayHelper::map(ComissionType::find()->where('is_deleted=:is', [':is' => 0])->all(),'id','comission_type_name' ), ['class' => 'form-control input-sm select2', 'prompt' => 'Pilih Komisi']);
                            ?>
                        </div>   
                        <div class="col-lg-6">
                            <?= $form->field($model, 'invoice_exchange_rate')->textInput(['class'=>'form-control input-sm angka']) ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="card">
            <div class="card-content">
                <div class="card-body padding-bottom-zero padding-top-zero">
                    <div class="row">
                        <div class="col-lg-12">
                            <?= $form->field($model, 'customer_id')
                                ->dropDownList(ArrayHelper::map(customer::find()->where('is_deleted=:is', [':is' => 0])->all(),'id','customerName' ), ['class' => 'form-control input-sm select2', 'prompt' => 'Pilih Customer']);
                            ?>
                        </div>
                    </div>
                    <div class="row mb-1 customer-debt">
                        <div class="col-lg-12">
                            <div class="form-group">
                                <label>Total Hutang</label>
                                <?= Html::textInput('customer_debt_amount', ($mode === 'update' && $model->customer->getCustomerDebtAmount() !== 0) ? $model->customer->getCustomerDebtAmount() : '', ['class' => 'form-control input-sm customer-debt-amount', 'readonly' => true]);?>
                            </div>
                        </div>
                    </div>
                    <div class="row mb-1 customer-debt">
                        <div class="col-lg-12">
                            <div class="form-group">
                                <label><?= (new Customer)->getAttributeLabel('customer_credit_limit')?></label>
                                <?= Html::textInput('customer_credit_limit', isset($model->customer->customer_credit_limit) ? $model->customer->customer_credit_limit : '', ['class' => 'form-control input-sm customer-credit-limit', 'readonly' => true]);?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<section class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-content">
                <div class="card-body padding-bottom-zero padding-top-zero">
                    <div class="row">
                        <div class="col-lg-4">
                            <?= $form->field($model, 'invoice_receiver')->textInput(['class'=>'form-control input-sm']) ?>
                        </div>
                        <div class="col-lg-4">
                           <?= $form->field($model, 'invoice_destination_province')->textInput(['class'=>'form-control input-sm']) ?>
                        </div>
                        <div class="col-lg-4">
                           <?= $form->field($model, 'invoice_destination_city')->textInput(['class'=>'form-control input-sm']) ?>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-4">
                           <?= $form->field($model, 'invoice_postal_code')->textInput(['class'=>'form-control input-sm ']) ?>
                        </div>
                        <div class="col-lg-4">
                            <?= $form->field($model, 'invoice_destination_address')->textInput(['class'=>'form-control input-sm']) ?>
                        </div>
                        
                        <div class="col-lg-4 padding-top-one mb-1">                           
                                <?= Html::checkbox('customer_signed', '', ['class' => 'i-checks customer-signed'])?>
                                <strong><span class="mr-3"> Sesuai Customer</span></strong>
                                <?php if($mode != 'update'): ?>
                                <?= Html::checkbox('is_create_po', '', ['class' => 'i-checks', 'checked' => true])?>
                                <strong><span> Otomatis Membuat PO</span></strong>
                                <?php endif; ?>
                        </div>
                        
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<section class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-content">
                <div class="card-body padding-top-zero">
                    <div class="row">
                        <div class="col-lg-4 ">
                                
                                <?= Html::dropDownList('', '', ArrayHelper::map(Brand::find()->where('brand_type=:type', [':type' => 'ppi'])->andWhere('is_deleted=:is', [':is' => 0])->all(),'id','brand_name'), ['class' => 'form-control input-sm select2', 'prompt' => 'Pilih Merek', 'id' => 'select-brand']) ?>
                        </div>
                        <div class="col-lg-4">
                                
                                <?= Html::dropDownList('', '', array(''), ['class' => 'form-control input-sm select2', 'prompt' => 'Pilih Kategori', 'id' => 'select-category']) ?>
                        </div>
                        <div class="col-lg-4">
                                
                                <?= Html::dropDownList('', '', array(''), ['class' => 'form-control input-sm select2', 'prompt' => 'Pilih Barang', 'id' => 'select-product']) ?>
                        </div>
                    </div>
                    <br>
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="row-template" style="display: none">
                                <table class="table-template">
                                    <tr id="row-{index}" class="row-item">
                                        <td>
                                            <center><span class="product-name"></span></center>
                                            <?=Html::hiddenInput('item[{index}][product_id]', '', array('class' => 'form-control product-id'));?>
                                        </td>
                                        <td>
                                            <?=Html::textInput('item[{index}][invoice_item_qty]', '', array('class' => 'form-control invoice-item-qty angka input-sm'));?>
                                        </td>
                                        <td>
                                            <?=Html::dropDownList('item[{index}][packaging_id]', '', ArrayHelper::map(Packaging::find()->where('is_deleted=:is', [':is' => 0])->all(), 'id', 'packaging_name'),
                                             array('class' => 'form-control packaging-id input-sm'));?>
                                        </td>
                                        <td>
                                            <div class="form-group position-relative has-icon-left">
                                                <input type="text" class="form-control form-control-sm input-sm angka invoice-item-disc-amount" name="item[{index}][invoice_item_disc_amount]" value="0">
                                                <div class="form-control-position">
                                                    <i class=" primary font-small-3">$</i>
                                                </div>
                                            </div>
                                            <?=Html::hiddenInput('item[{index}][invoice_item_disc_percent]', '', array('class' => 'form-control invoice-item-disc-percent'));?>
                                        </td>
                                        <td class="text-right">
                                            <span class="invoice-item-price-label"></span>
                                            <?=Html::hiddenInput('item[{index}][invoice_item_price]', '', array('class' => 'form-control invoice-item-price'));?>
                                        </td>
                                        <td class="text-right">
                                            <span class="invoice-item-row-total-label"></span>
                                            <?=Html::hiddenInput('item[{index}][invoice_item_row_total]', '', array('class' => 'form-control invoice-item-row-total'));?>
                                        </td>
                                        <td><a href="#" class="btn btn-outline-danger btn-sm btn-remove"><i class="la la-trash"></i></a></td>
                                    </tr>
                                </table>
                            </div>
                            <div class="table-responsive">
                                <table class="table table-striped table-bordered table-item">
                                    <thead>
                                        <tr>
                                            <th style="width: 25%"><center><?= (new SalesInvoiceItem)->getAttributeLabel('product_id')?></center></th>
                                            <th style="width: 10%"><center><?= (new SalesInvoiceItem)->getAttributeLabel('invoice_item_qty')?></center></th>
                                            <th style="width: 10%"><center><?= (new SalesInvoiceItem)->getAttributeLabel('packaging_id')?></center></th>
                                            <th style="width: 15%;"><center><?= (new SalesInvoiceItem)->getAttributeLabel('invoice_item_disc_amount')?></center></th>
                                            <th style="width: 15%"><center><?= (new SalesInvoiceItem)->getAttributeLabel('invoice_item_price')?></center></th> 
                                            <th style="width: 20%;"><center><?= (new SalesInvoiceItem)->getAttributeLabel('invoice_item_row_total')?></center></th>
                                            <th style="width: 5%;"></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    <?php if (!empty($items)):?>
                                        <?php foreach($items as $i => $item):?>
                                            <?php if ($i !== "{index}"):?>
                                        <tr id="row-<?=$i?>" class="row-item">
                                            <td>
                                                <center><span class="product-name"><?= isset($item['product_name']) ? $item['product_name'] : '' ?></span></center>
                                                <?=Html::hiddenInput('item['.$i.'][product_id]', $item['product_id'], array('class' => 'form-control product-id'));?>
                                            </td>
                                            <td>
                                                <?=Html::textInput('item['.$i.'][invoice_item_qty]', $item['invoice_item_qty'], array('class' => 'form-control invoice-item-qty angka input-sm'));?>
                                            </td>
                                            <td>
                                                <?=Html::dropDownList('item['.$i.'][packaging_id]', $item['packaging_id'], ArrayHelper::map(Packaging::find()->where('is_deleted=:is', [':is' => 0])->all(), 'id', 'packaging_name'),
                                                 array('class' => 'form-control packaging-id input-sm'));?>
                                            </td>
                                            <td>
                                                <div class="form-group position-relative has-icon-left">
                                                    <input type="text" class="form-control form-control-sm input-sm angka invoice-item-disc-amount" name="item[<?php echo $i; ?>][invoice_item_disc_amount]" value="<?php echo $item['invoice_item_disc_amount']; ?>">
                                                    <div class="form-control-position">
                                                        <i class=" primary font-small-3">$</i>
                                                    </div>
                                                </div>
                                                <?=Html::hiddenInput('item['.$i.'][invoice_item_disc_percent]', $item['invoice_item_disc_percent'], array('class' => 'form-control invoice-item-disc-percent'));?>
                                            </td>
                                            <td class="text-right">
                                                <span class="invoice-item-price-label"><?= isset($item['invoice_item_price']) ? CurrencyComponent::formatMoney($item['invoice_item_price'],0,',','.', Product::CURRENCY_DOLAR) : '' ?></span>
                                                <?=Html::hiddenInput('item['.$i.'][invoice_item_price]', $item['invoice_item_price'], array('class' => 'form-control invoice-item-price'));?>
                                            </td>
                                            <td class="text-right">
                                                <span class="invoice-item-row-total-label"><?= isset($item['invoice_item_row_total']) ? $item['invoice_item_row_total'] : '' ?></span>
                                                <?=Html::hiddenInput('item['.$i.'][invoice_item_row_total]', $item['invoice_item_row_total'], array('class' => 'form-control invoice-item-row-total'));?>
                                            </td>
                                            <td><a href="#" class="btn btn-outline-danger btn-sm btn-remove"><i class="la la-trash"></i></a></td>
                                        </tr>
                                            <?php endif;?>
                                        <?php endforeach;?>
                                    <?php endif;?>
                                    <tr class="row-footer-subtotal">
                                        <td colspan="5" class="text-right"><span><b><?= (new SalesInvoice)->getAttributeLabel('invoice_subtotal')?></b></span></td>
                                        <td class="text-right">
                                            <strong><span class="invoice-subtotal-label">$0</span></strong>
                                        </td>
                                        <td><?=$form->field($model, 'invoice_subtotal')->hiddenInput()->label(false)?></td>
                                    </tr>
                                    <tr>
                                        <td colspan="5" class="text-right td-disc"><span><b><?= (new SalesInvoice)->getAttributeLabel('invoice_disc_amount')?></b></span></td>
                                        <td>
                                            <div class="row">
                                                <div class="col-lg-4 padding-right-zero">
                                                    <div class="form-group position-relative input-percent">
                                                        <input type="text" class="form-control form-control-sm input-sm invoice-disc-percent text-right angka mb-1" name="SalesInvoice[invoice_disc_percent]" value="<?= isset($model->invoice_disc_percent) ? $model->invoice_disc_percent : 0 ?>">
                                                        <div class="form-control-position">
                                                            <i class="primary font-small-4">%</i>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-lg-8">
                                                    <div class="form-group position-relative has-icon-left">
                                                        <input type="text" class="form-control form-control-sm input-sm angka invoice-disc-amount" name="SalesInvoice[invoice_disc_amount]" value="<?= isset($model->invoice_disc_amount) ? $model->invoice_disc_amount : 0 ?>">
                                                        <div class="form-control-position">
                                                            <i class=" primary font-small-3 currency-label">$</i>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                        <td></td>
                                    </tr>
                                    <tr>
                                        <td colspan="5"></td>
                                        <td>
                                            <div class="row">
                                                <!-- <div class="col-lg-4 padding-right-zero">
                                                    <div class="form-group position-relative input-percent"> -->
                                                        <input type="hidden" class="form-control form-control-sm input-sm invoice-disc-percent2 text-right angka mb-1" name="SalesInvoice[invoice_disc_percent2]" value="<?= isset($model->invoice_disc_percent2) ? $model->invoice_disc_percent2 : 0 ?>">
                                                        <!-- <div class="form-control-position">
                                                            <i class="primary font-small-4">%</i>
                                                        </div>
                                                    </div>
                                                </div> -->
                                                <div class="col-lg-12">
                                                    <div class="form-group position-relative has-icon-left">
                                                        <input type="text" class="form-control form-control-sm input-sm angka invoice-disc-amount2" name="SalesInvoice[invoice_disc_amount2]" value="<?= isset($model->invoice_disc_amount2) ? $model->invoice_disc_amount2 : 0 ?>">
                                                        <div class="form-control-position">
                                                            <i class=" primary font-small-3 currency-label">$</i>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                        <td></td>
                                    </tr>
                                    <tr>
                                        <td colspan="5" class="text-right td-tax"><span><b><?= (new SalesInvoice)->getAttributeLabel('invoice_tax_amount')?></b></span></td>
                                        <td>
                                            <div class="row">
                                                <div class="col-lg-4 padding-right-zero">
                                                    <div class="form-group position-relative input-percent">
                                                        <input type="text" class="form-control form-control-sm input-sm invoice-tax-percent text-right angka mb-1" name="SalesInvoice[invoice_tax_percent]" value="<?= isset($model->invoice_tax_percent) ? $model->invoice_tax_percent : 0 ?>">
                                                        <div class="form-control-position">
                                                            <i class="primary font-small-4">%</i>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-lg-8">
                                                     <div class="form-group position-relative has-icon-left">
                                                        <input type="text" class="form-control form-control-sm input-sm invoice-tax-amount angka" name="SalesInvoice[invoice_tax_amount]" value="<?= isset($model->invoice_tax_amount) ? $model->invoice_tax_amount : 0 ?>">
                                                        <div class="form-control-position">
                                                            <i class=" primary font-small-3 currency-label">$</i>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                        <td></td>
                                    </tr>
                                    <tr>
                                        <td colspan="5" class="text-right"><span><b><?= (new SalesInvoice)->getAttributeLabel('invoice_shipping_cost')?></b></span></td>
                                        <td>
                                            <div class="form-group position-relative has-icon-left">
                                                <input type="text" class="form-control form-control-sm input-sm invoice-shipping-cost angka" name="SalesInvoice[invoice_shipping_cost]" value="<?= isset($model->invoice_shipping_cost) ? $model->invoice_shipping_cost : 0 ?>">
                                                <div class="form-control-position">
                                                    <i class=" primary font-small-3 currency-label">$</i>
                                                </div>
                                            </div>
                                        </td>
                                        <td></td>
                                    </tr>
                                    <tr>
                                        <td colspan="5" class="text-right"><span><b><?= (new SalesInvoice)->getAttributeLabel('invoice_grand_total')?></b></span></td>
                                        <td class="text-right">
                                            <strong><span class="invoice-grand-total">$0</span></strong>
                                        </td>
                                        <td> <?=$form->field($model, 'invoice_grand_total')->hiddenInput()->label(false)?></td>
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
function toggleCustomerDebt(){
    if($('#salesinvoice-customer_id').val() == '')
        $('.customer-debt').hide();
    else
        $('.customer-debt').show();
}

function setDataProduct(idbrand, idcategory){
    var url = $('#baseUrl').val();

    $.get(url+'/getitemproduct',{brandId:idbrand, categoryId:idcategory},function(response){
        let data = $.parseJSON(response);
        $('#select-product').empty();
        $('#select-product').append(
            '<option value="">Pilih Barang</option>'
        );
        $.each(data, function(key, value){
            $('#select-product').append(
                    '<option value='+data[key].id+'>'+data[key].product_code+' -- '+data[key].product_name+'</option>'
              );
        });

    });
}

function countItemSubtotal(tr)
{
    var qty = tr.find(".invoice-item-qty").val();
    var price = tr.find(".invoice-item-price").val();
    var disc = tr.find(".invoice-item-disc-amount").val() !== '' ? tr.find(".invoice-item-disc-amount").val() : 0;
    // var percent2 = tr.find(".invoice-item-disc-percent2").val() !== '' ? tr.find(".invoice-item-disc-percent2").val() : 0;
    // var disc2 = ( (parseFloat(price) - parseFloat(disc)) *  parseFloat(percent2) ) / 100; 
    // tr.find(".invoice-item-disc-amount2").val(disc2);
    // var subtotal = parseFloat(qty) * parseFloat(price);
    var subtotal = (parseFloat(price) - parseFloat(disc)) * parseFloat(qty);

    if($('#salesinvoice-invoice_exchange_rate').val() > 1){
        subtotal = parseFloat($('#salesinvoice-invoice_exchange_rate').val()) * parseFloat(subtotal);
        tr.find(".invoice-item-row-total-label").html("Rp"+number_format(subtotal, 0, ',', '.'));
        tr.find(".invoice-item-row-total").val(subtotal);
    }
    else{
        tr.find(".invoice-item-row-total-label").html('$'+format_usd(subtotal));
        tr.find(".invoice-item-row-total").val(subtotal);
    }
}

function discontaxnull(){
    $('.invoice-disc-amount').val("0");
    $('.invoice-disc-percent').val("0");
    $('.invoice-disc-percent2').val("0");
    $('.invoice-tax-amount').val("0");
    $('.invoice-shipping-cost').val("0");
    $('.invoice-tax-percent').val("0");
}

function countRowItem () {
    if($('.table-item .row-item').length >= 11){
        $('#select-product').attr('disabled', true);
    }
    else{
        $('#select-product').removeAttr('disabled');
    }
}

function addRow(product, id, price)
{
    var index = 0;
    if (typeof $(".table-item .row-item").last().attr("id") != 'undefined')
        index = parseInt($(".table-item .row-item").last().attr("id").split("-")[1]) + 1;
        
    var clone = $(".table-template .row-item").clone().attr("id", "row-"+index);
    
    clone.html(function(i, oldTr) {return oldTr.replace(/\{index}/g, index);});
    
    $(".row-footer-subtotal").before(clone);

    var tr = $(".table-item").find("#row-"+index);
    tr.find(".product-name").html(product);
    tr.find(".product-id").val(id);
    tr.find(".invoice-item-qty").val('1');
    tr.find(".invoice-item-price-label").html('$'+format_usd(price));
    tr.find(".invoice-item-price").val(price);
    if($('#salesinvoice-invoice_exchange_rate').val() > 1){
        price = parseFloat($('#salesinvoice-invoice_exchange_rate').val()) * price;
        tr.find(".invoice-item-row-total").val(price);
        tr.find(".invoice-item-row-total-label").html("Rp"+number_format(price, 0, ',', '.'));
    }
    else{
        tr.find(".invoice-item-row-total").val(price);
        tr.find(".invoice-item-row-total-label").html('$'+format_usd(price));
    }
    countTotal();
    countRowItem();
}

function updateAllRow(amount){
    var row = $('.row-item');
    
    for(var i = 0; i < row.length; i++){
        var qty = $(row[i]).find('.invoice-item-qty').val();
        var discamount = $(row[i]).find('.invoice-item-disc-amount').val();
        // var discamount2 = $(row[i]).find('.invoice-item-disc-amount2').val();
        var price = $(row[i]).find('.invoice-item-price').val();
        
        qty = qty !== "" ? qty : 1;
        // discamount2 = discamount2 !== "" ? discamount2 : 0;
        discamount = discamount !== "" ? discamount : 0;
        var rowTotal = ( parseFloat(price)  - parseFloat(discamount) ) * parseFloat(qty);

        if(amount != 1){
            rowTotal = parseFloat(amount) * rowTotal;
            $(row[i]).find(".invoice-item-row-total").val(rowTotal);
            $(row[i]).find(".invoice-item-row-total-label").html("Rp"+number_format(rowTotal, 0, ',', '.'));
        }
        else{
            $(row[i]).find(".invoice-item-row-total").val(rowTotal);
            $(row[i]).find(".invoice-item-row-total-label").html('$'+format_usd(rowTotal));
        }
    }

    countTotal();
    
}

function countSubtotal()
{
    var subtotal = 0;
    $(".table-item .invoice-item-row-total").each(function(){
        subtotal += parseFloat($(this).val());
    });

    $("#salesinvoice-invoice_subtotal").val(subtotal);
    if($('#salesinvoice-invoice_exchange_rate').val() > 1)
        $(".invoice-subtotal-label").html("Rp"+number_format(subtotal, 0, ',', '.'));
    else
        $(".invoice-subtotal-label").html('$'+format_usd(subtotal));
    return subtotal;
}

function discplus(val = ''){
    var subtotal = $('#salesinvoice-invoice_subtotal').val();
    var disc1 = $('.invoice-disc-percent').val();
    var disc2 = $('.invoice-disc-percent2').val();
    
    if(disc1 == '')
        disc1 = 0;
    if(disc2 == '')
        disc2 = 0;

    var discamount = parseFloat(subtotal) * (parseFloat(disc1) + parseFloat(disc2)) / 100;
    var total = parseFloat(subtotal) + parseFloat(discamount);

    if( val = 'discamount')
        return discamount;

    return subtotal;
}

function countTotal()
{
    countSubtotal();

    var subtotal = $('#salesinvoice-invoice_subtotal').val();
    var taxamount = $('.invoice-tax-amount').val()
    var shippingcost = $('.invoice-shipping-cost').val()

    
    if(taxamount == '')
        taxamount = 0;
    if(shippingcost == '')
        shippingcost = 0;

    var discamount = discplus('discamount');

    total = parseFloat(subtotal) - parseFloat(discamount) + parseFloat(taxamount) + parseFloat(shippingcost);
    
    $("#salesinvoice-invoice_grand_total").val(total);
    if($('#salesinvoice-invoice_exchange_rate').val() > 1){
        $(".invoice-grand-total").html("Rp"+number_format(total, 0, ',', '.'));
        $(".currency-label").html("Rp");
    }
    else{
        $(".invoice-grand-total").html('$'+format_usd(total));
        $(".currency-label").html("$");
    }

    return total;
}

$(document).on('click', '.btn-remove', function(e){

    var tr = $(this).closest('tr');
    tr.remove();
    countTotal();
    countRowItem();
    e.preventDefault();

});

$(document).on('change', '#select-brand', function(){
    var id = $(this).val();
    var url = $('#baseUrl').val();

    $.get(url+'/getitemcategory',{id:id},function(response){
        let data = $.parseJSON(response);
        $('#select-category').empty();
        $('#select-category').append(
            '<option>Pilih Kategori</option>'
        );
        $('#select-product').empty();
        $('#select-product').append(
            '<option value="">Pilih Barang</option>'
        );
        $.each(data, function(key, value){
            $('#select-category').append(
                    '<option value='+data[key].id+'>'+data[key].category_name+'</option>'
              );
        });

    });
});

$(document).on('change', '#select-category', function(){
    var categoryId = $(this).val();
    var brandId = $('#select-brand').val();
    setDataProduct(brandId, categoryId);
    
});	

$(document).on('change', '#select-product', function(){
    var id = $(this).val();
    var url = $('#baseUrl').val();

    if($(this).val() != ''){
        $.get(url+'/getitemrow',{id:id},function(response){
            let data = $.parseJSON(response);
            
            addRow(data.product_name, data.id, data.product_sell_price);
        });
    }
});

$(document).on('change', '#salesinvoice-customer_id', function(){
    var id = $(this).val();
    var url = $('#baseUrl').val();

    $.get(url+'/getitemcustomer',{id:id},function(response){
        let data = $.parseJSON(response);
        $('.customer-debt-amount').val('$'+format_usd(data.customer_debt_amount));
        $('.customer-credit-limit').val('$'+format_usd(data.customer_credit_limit));
        toggleCustomerDebt();
    });
});

$(document).on('change', ".invoice-item-disc-amount", function(e){
    var tr = $(this).closest('tr');
    if($(this).val() == ''){
        tr.find('.invoice-item-disc-amount').val(0);
    }
});

// $(document).on('change', ".invoice-item-disc-percent2", function(e){
//     var tr = $(this).closest('tr');
//     if($(this).val() == ''){
//         tr.find('.invoice-item-disc-percent2').val(0);
//     }
// });

$(document).on('input', ".invoice-item-disc-amount", function(e){
    var tr = $(this).closest('tr');
    var tmp = $(this).val() !== '' ? $(this).val() : 0;
    var productPrice = tr.find(".invoice-item-price").val();
    var itemPercent = tr.find(".invoice-item-disc-percent");

    var percent = parseFloat(tmp) / parseFloat(productPrice) * 100;
    itemPercent.val(percent);

    discontaxnull();
    countItemSubtotal(tr)
    countTotal();
});

// $(document).on('input', ".invoice-item-disc-percent2", function(e){
//    var tr = $(this).closest('tr');
//     discontaxnull();
//     countItemSubtotal(tr)
//     countTotal();
// });

$(document).on('input', ".invoice-item-qty", function(e){
    var tr = $(this).closest('tr');
    if($(this).val() != ''){
        discontaxnull();
        countItemSubtotal(tr)
        countTotal();
    }
});

// function updateTotalRow(qty, amount, percent, price, label){
//     var totalqty =0
//     if(qty != '')
//         totalqty = parseFloat(qty) * parseFloat(price);
//     else
//         totalqty = price;

//     if(label == 'total'){
//         if(amount == '')
//             amount = 0;
//         var total = parseFloat(totalqty) - parseFloat(amount);
//         return total;
//     }

//     if(label == 'amount'){
//         if(percent == '')
//             percent = 0;
//         var totalamount = parseFloat(totalqty) * parseFloat(percent) / 100;
//         return totalamount;
//     }    

//     if(label == 'percent'){
//         if(amount == '')
//             amount = 0;
//         var totalpercent = parseFloat(amount) / parseFloat(totalqty) * 100;
//         return totalpercent;
//     }
// }

$(document).on('input', ".invoice-disc-amount", function(e){
    if($(this).val() != ''){
        var subtotal = $("#salesinvoice-invoice_subtotal").val();
        var percent = parseFloat($(this).val()) / parseFloat(subtotal) * 100;
        
        $('.invoice-disc-percent').val(percent);
    }else{
        $('.invoice-disc-percent').val(0);
    }
    countTotal();
});

$(document).on('input', ".invoice-disc-percent", function(e){
    if($(this).val() != ''){
        var subtotal = $("#salesinvoice-invoice_subtotal").val();
        var amount = parseFloat(subtotal) * parseFloat($(this).val()) / 100;
        
        // if($('#salesinvoice-invoice_exchange_rate').val() > 1)
        //     $('.invoice-disc-amount').val(number_format(amount, 0, ',', '.'));
        // else
        //     $('.invoice-disc-amount').val(format_usd(amount));
        $('.invoice-disc-amount').val(amount);
    }else{
        $('.invoice-disc-amount').val(0);
    }

    countTotal();
});

$(document).on('input', ".invoice-disc-amount2", function(e){
    if($(this).val() != ''){
        var subtotal = $("#salesinvoice-invoice_subtotal").val();
        var percent = parseFloat($(this).val()) / parseFloat(subtotal) * 100;
        
        $('.invoice-disc-percent2').val(percent);
    }else{
        $('.invoice-disc-percent2').val(0); 
    }
    countTotal();
});

$(document).on('input', ".invoice-disc-percent2", function(e){
    if($(this).val() != ''){
        var subtotal = $("#salesinvoice-invoice_subtotal").val();
        var amount = parseFloat(subtotal) * parseFloat($(this).val()) / 100;
        $('.invoice-disc-amount2').val(amount);
    }else{
        $('.invoice-disc-amount2').val(0);
    }

    countTotal();
});

$(document).on('input', ".invoice-tax-amount", function(e){
    if($(this).val() != ''){
        var subtotal = $("#salesinvoice-invoice_subtotal").val() - discplus();
        var percent = parseFloat($(this).val()) / parseFloat(subtotal) * 100;
        
        $('.invoice-tax-percent').val(percent);
    }else{
        $('.invoice-tax-percent').val(0);
    }
    countTotal();
});

$(document).on('input', ".invoice-tax-percent", function(e){
    if($(this).val() != ''){
        var subtotal = $("#salesinvoice-invoice_subtotal").val() - discplus();
        var amount = parseFloat(subtotal) * parseFloat($(this).val()) / 100;
        
        // if($('#salesinvoice-invoice_exchange_rate').val() > 1)
        //     $('.invoice-tax-amount').val(number_format(amount, 0, ',', '.'));
        // else
        //     $('.invoice-tax-amount').val(format_usd(amount));
        $('.invoice-tax-amount').val(amount);
    }else{
        $('.invoice-tax-amount').val(0);
    }

    countTotal();
});


$(document).on('input', ".invoice-shipping-cost", function(e){
    countTotal();
});


$(document).on('click', 'saveandnew', function(){
    $('#hiddensaveandnew').val(1);
});

$(document).on('change', '#salesinvoice-invoice_exchange_rate', function(){
	countTotal();
    discontaxnull();
    updateAllRow($(this).val());
});

$('.customer-signed').on('ifChecked',function(){
    var customerId = $('#salesinvoice-customer_id').val();
    if(customerId == ''){
        alert("Customer belum di pilih !");
        setTimeout(function(){
            $('.customer-signed').iCheck('uncheck');
        },0);
    }
    else{
        var url = $('#baseUrl').val();
        $.get(url+'/getcustomer',{id:customerId},function(response){
            let data = $.parseJSON(response);

            $('#salesinvoice-invoice_receiver').val(data.customer_name);
            $('#salesinvoice-invoice_postal_code').val(data.customer_store_postal_code);
            $('#salesinvoice-invoice_destination_province').val(data.customer_province);
            $('#salesinvoice-invoice_destination_address').val(data.customer_store_address);
            $('#salesinvoice-invoice_destination_city').val(data.customer_city);
        });
    }

    
})
.on('ifUnchecked',function(){
    $('#salesinvoice-invoice_receiver').val("");
    $('#salesinvoice-invoice_postal_code').val("");
    $('#salesinvoice-invoice_destination_province').val("");
    $('#salesinvoice-invoice_destination_address').val("");
    $('#salesinvoice-invoice_destination_city').val("");
});

$(document).ready(function(){
    updateAllRow($('#salesinvoice-invoice_exchange_rate').val());
    countTotal();
    countRowItem();
    toggleCustomerDebt();
});

</script>