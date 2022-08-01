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
use common\models\Supplier;
use common\models\Product;
use common\models\Brand;
use common\models\Category;
use common\models\PurchaseOrder;
use common\models\PurchaseOrderItem;

$this->title = BaseController::getCustomPageTitle(BaseController::$page_caption);
$toolbar[] = ButtonComponent::getBackButton();
$toolbar[] = ButtonComponent::getSaveButton();
	
BaseController::$toolbar = $toolbar;
	
foreach(Yii::$app->session->getAllFlashes() as $key => $message)
	echo '<div class="alert alert-' . $key . '">' . $message . "</div>\n";

?>
<input id="baseUrl" type="hidden" value="<?=Url::base()?>/purchase/purchaseorder">
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
                            <?= $form->field($model, 'purchase_order_date')->textInput(['class'=>'form-control input-sm date-inputmask', "data-inputmask" =>"'alias': 'datetime','inputFormat': 'dd-mm-yyyy'"]) ?>
                        </div>
                        <div class="col-lg-6">
                            <?= $form->field($model, 'purchase_order_notes')->textInput(['class'=>'form-control input-sm ']) ?>
                        </div>
                        
                    </div>
                    <div class="row">
                        <div class="col-lg-6">
                            <?= $form->field($model, 'purchase_order_status')
                                ->dropDownList($model->getStatusLabel(), ['class' => 'form-control input-sm select2']);
                            ?>
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
                                            <?=Html::textInput('item[{index}][purchase_order_item_qty]', '', array('class' => 'form-control invoice-item-qty angka input-sm'));?>
                                        </td>
                                        <td>
                                            <div class="form-group position-relative has-icon-left">
                                                <input type="text" class="form-control form-control-sm input-sm angka invoice-item-disc-amount" name="item[{index}][purchase_order_item_disc_amount]" value="0">
                                                <div class="form-control-position">
                                                    <i class=" primary font-small-3">$</i>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="form-group position-relative input-percent">
                                                <input type="text" class="form-control form-control-sm input-sm angka text-right invoice-item-disc-percent" name="item[{index}][purchase_order_item_disc_percent]" value="0">
                                                <div class="form-control-position">
                                                    <i class="primary font-small-4">%</i>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="text-right">
                                            <span class="invoice-item-price-label"></span>
                                            <?=Html::hiddenInput('item[{index}][purchase_order_item_price]', '', array('class' => 'form-control invoice-item-price'));?>
                                        </td>
                                        <td class="text-right">
                                            <span class="invoice-item-row-total-label"></span>
                                            <?=Html::hiddenInput('item[{index}][purchase_order_item_row_total]', '', array('class' => 'form-control invoice-item-row-total'));?>
                                        </td>
                                        <td><a href="#" class="btn btn-outline-danger btn-sm btn-remove"><i class="la la-trash"></i></a></td>
                                    </tr>
                                </table>
                            </div>
                            <div class="table-responsive">
                                <table class="table table-striped table-bordered table-item">
                                    <thead>
                                        <tr>
                                            <th style="width: 20%"><center><?= (new PurchaseOrderItem)->getAttributeLabel('product_id')?></center></th>
                                            <th style="width: 10%"><center><?= (new PurchaseOrderItem)->getAttributeLabel('purchase_order_item_qty')?></center></th>
                                            <th style="width: 15%;"><center><?= (new PurchaseOrderItem)->getAttributeLabel('purchase_order_item_disc_amount')?></center></th>
                                            <th style="width: 15%;"><center><?= (new PurchaseOrderItem)->getAttributeLabel('purchase_order_item_disc_percent')?></center></th>
                                            <th style="width: 15%"><center><?= (new PurchaseOrderItem)->getAttributeLabel('purchase_order_item_price')?></center></th>
                                            <th style="width: 20%;"><center><?= (new PurchaseOrderItem)->getAttributeLabel('purchase_order_item_row_total')?></center></th>
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
                                                <?=Html::textInput('item['.$i.'][purchase_order_item_qty]', $item['purchase_order_item_qty'], array('class' => 'form-control invoice-item-qty angka input-sm'));?>
                                            </td>
                                            <td>
                                                <div class="form-group position-relative has-icon-left">
                                                    <input type="text" class="form-control form-control-sm input-sm angka invoice-item-disc-amount" name="item[<?php echo $i; ?>][purchase_order_item_disc_amount]" value="<?php echo $item['purchase_order_item_disc_amount']; ?>">
                                                    <div class="form-control-position">
                                                        <i class=" primary font-small-3">$</i>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="form-group position-relative input-percent">
                                                    <input type="text" class="form-control form-control-sm input-sm angka text-right invoice-item-disc-percent" name="item[<?php echo $i; ?>][purchase_order_item_disc_percent]" value="<?php echo $item['purchase_order_item_disc_percent']; ?>">
                                                    <div class="form-control-position">
                                                        <i class="primary font-small-4">%</i>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="text-right">
                                                <span class="invoice-item-price-label"><?= isset($item['purchase_order_item_price']) ? $item['purchase_order_item_price'] : '' ?></span>
                                                <?=Html::hiddenInput('item['.$i.'][purchase_order_item_price]', $item['purchase_order_item_price'], array('class' => 'form-control invoice-item-price'));?>
                                            </td>
                                            <td class="text-right">
                                                <span class="invoice-item-row-total-label"><?= isset($item['purchase_order_item_row_total']) ? $item['purchase_order_item_row_total'] : '' ?></span>
                                                <?=Html::hiddenInput('item['.$i.'][purchase_order_item_row_total]', $item['purchase_order_item_row_total'], array('class' => 'form-control invoice-item-row-total'));?>
                                            </td>
                                            <td><a href="#" class="btn btn-outline-danger btn-sm btn-remove"><i class="la la-trash"></i></a></td>
                                        </tr>
                                            <?php endif;?>
                                        <?php endforeach;?>
                                    <?php endif;?>
                                    <tr>
                                        <td colspan="5" class="text-right"><span><b><?= $model->getAttributeLabel('purchase_order_subtotal')?></b></span></td>
                                        <td class="text-right">
                                            <strong><span class="invoice-subtotal-label">$0</span></strong>
                                        </td>
                                        <td><?=$form->field($model, 'purchase_order_subtotal')->hiddenInput()->label(false)?></td>
                                    </tr>
                                    <tr>
                                        <td colspan="5" class="text-right"><span><b><?= $model->getAttributeLabel('purchase_order_disc_amount')?></b></span></td>
                                        <td>
                                             <div class="form-group position-relative input-percent">
                                                <input type="text" class="form-control form-control-sm input-sm invoice-disc-percent text-right angka mb-1" name="PurchaseOrder[purchase_order_disc_percent]" value="<?= isset($model->purchase_order_disc_percent) ? $model->purchase_order_disc_percent : 0 ?>">
                                                <div class="form-control-position">
                                                    <i class="primary font-small-4">%</i>
                                                </div>
                                            </div>
                                             <div class="form-group position-relative has-icon-left">
                                                <input type="text" class="form-control form-control-sm input-sm angka invoice-disc-amount" name="PurchaseOrder[purchase_order_disc_amount]" value="<?= isset($model->purchase_order_disc_amount) ? $model->purchase_order_disc_amount : 0 ?>">
                                                <div class="form-control-position">
                                                    <i class=" primary font-small-3">$</i>
                                                </div>
                                            </div>
                                        </td>
                                        <td></td>
                                    </tr>
                                    <tr>
                                        <td colspan="5" class="text-right"><span><b><?= (new PurchaseOrder)->getAttributeLabel('purchase_order_grand_total')?></b></span></td>
                                        <td class="text-right">
                                            <strong><span class="invoice-grand-total">$0</span></strong>
                                        </td>
                                        <td> <?=$form->field($model, 'purchase_order_grand_total')->hiddenInput()->label(false)?></td>
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
                    '<option value='+data[key].id+'>'+data[key].product_name+'</option>'
              );
        });

    });
}

function addRow(product, id, price)
{
    var index = 0;
    if (typeof $(".table-item .row-item").last().attr("id") != 'undefined')
        index = parseInt($(".table-item .row-item").last().attr("id").split("-")[1]) + 1;
        
    var clone = $(".table-template .row-item").clone().attr("id", "row-"+index);
    
    clone.html(function(i, oldTr) {return oldTr.replace(/\{index}/g, index);});
    
    $(".table-item").prepend(clone);

    var tr = $(".table-item").find("#row-"+index);
    tr.find(".product-name").html(product);
    tr.find(".product-id").val(id);
    tr.find(".invoice-item-price-label").html('$'+format_usd(price));
    tr.find(".invoice-item-row-total-label").html('$'+format_usd(price));
    tr.find(".invoice-item-price").val(price);
    tr.find(".invoice-item-row-total").val(price);
    
    countTotal();
}

function countSubtotal()
{
    var subtotal = 0;
    $(".table-item .invoice-item-row-total").each(function(){
        subtotal += parseFloat($(this).val());
    });

    $("#purchaseorder-purchase_order_subtotal").val(subtotal);
    $(".invoice-subtotal-label").html('$'+format_usd(subtotal));
    return subtotal;
}

function countTotal()
{
    countSubtotal();

    var subtotal = $('#purchaseorder-purchase_order_subtotal').val();
    var discamount = $('.invoice-disc-amount').val();

    if(discamount == '')
        discamount = 0;

    total = parseFloat(subtotal) - parseFloat(discamount);
    
    $("#purchaseorder-purchase_order_grand_total").val(total);
    $(".invoice-grand-total").html('$'+format_usd(total));
    return total;
}

$(document).on('click', '.btn-remove', function(e){

    var tr = $(this).closest('tr');
    tr.remove();
    countTotal();
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


$(document).on('input', ".invoice-item-disc-amount", function(e){
    var tr = $(this).closest('tr');
    if($(this).val() != ''){
        var productPrice = tr.find(".invoice-item-price").val();
        var qty = tr.find(".invoice-item-qty").val();
        var itemPercent = tr.find(".invoice-item-disc-percent");
        var subTotal = tr.find(".invoice-item-row-total");
        var subTotalLabel = tr.find(".invoice-item-row-total-label");
        var totalqty = 0;
        if(qty != '')
            totalqty = parseFloat(productPrice) * parseFloat(qty);
        else
            totalqty = productPrice;

        var percent = parseFloat($(this).val()) / parseFloat(totalqty) * 100;
        var subtotal = parseFloat(totalqty) - parseFloat($(this).val());
        
        itemPercent.val(percent);
        subTotal.val(subtotal);
        subTotalLabel.html('$'+format_usd(subtotal));
        
        countTotal();
    }
});

$(document).on('input', ".invoice-item-disc-percent", function(e){
    var tr = $(this).closest('tr');
    if($(this).val() != ''){
        var productPrice = tr.find(".invoice-item-price").val();
        var qty = tr.find(".invoice-item-qty").val();
        var itemAmount = tr.find(".invoice-item-disc-amount");
        var subTotal = tr.find(".invoice-item-row-total");
        var subTotalLabel = tr.find(".invoice-item-row-total-label");
        var totalqty = 0;
        if(qty != '')
            totalqty = parseFloat(productPrice) * parseFloat(qty);
        else
            totalqty = productPrice;

        var amount = parseFloat(totalqty) * parseFloat($(this).val()) / 100;
        var subtotal = parseFloat(totalqty) - parseFloat(amount);
        
        itemAmount.val(amount);
        subTotal.val(subtotal);
        subTotalLabel.html('$'+format_usd(subtotal));
        
        countTotal();
    }
});

$(document).on('input', ".invoice-item-qty", function(e){
    var tr = $(this).closest('tr');
    var qty = 0;
    if($(this).val() != '')
        qty = $(this).val();
    else
        qty = 1;

    var productPrice = tr.find(".invoice-item-price").val();
    var subTotal = tr.find(".invoice-item-row-total");
    var subTotalLabel = tr.find(".invoice-item-row-total-label");
    tr.find(".invoice-item-disc-amount").val(0);
    tr.find(".invoice-item-disc-percent").val(0);
    
    var subtotal = parseFloat(productPrice) * parseFloat(qty);
    
    subTotal.val(subtotal);
    subTotalLabel.html('$'+format_usd(subtotal));
    
    countTotal();
    
});

$(document).on('input', ".invoice-disc-amount", function(e){
    if($(this).val() != ''){
        var subtotal = $("#purchaseorder-purchase_order_subtotal").val();
        var percent = parseFloat($(this).val()) / parseFloat(subtotal) * 100;
        
        $('.invoice-disc-percent').val(percent);
    }else{
        $('.invoice-disc-percent').val(0);
    }
    countTotal();
});

$(document).on('input', ".invoice-disc-percent", function(e){
    if($(this).val() != ''){
        var subtotal = $("#purchaseorder-purchase_order_subtotal").val();
        var amount = parseFloat(subtotal) * parseFloat($(this).val()) / 100;
        
        $('.invoice-disc-amount').val(amount);
    }else{
        $('.invoice-disc-amount').val(0);
    }

    countTotal();
});

$(document).on('click', 'saveandnew', function(){
	$('#hiddensaveandnew').val(1);
});

$(document).ready(function(){
    countTotal();
});

</script>