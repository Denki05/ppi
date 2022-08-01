<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
// use yii\widgets\ActiveForm;
use yii\bootstrap\ActiveForm;
use frontend\components\LabelComponent;
use frontend\components\AccessComponent;
use frontend\components\ButtonComponent;
use app\components\BaseController;
use common\models\Customer;
use common\models\Product;
use common\models\Brand;
use common\models\Category;
use common\models\IndentItem;
use common\components\CurrencyComponent;

$this->title = BaseController::getCustomPageTitle(BaseController::$page_caption);

$toolbar[] = ButtonComponent::getBackButton();
$toolbar[] = ButtonComponent::getSaveButton();
	
BaseController::$toolbar = $toolbar;
	
foreach(Yii::$app->session->getAllFlashes() as $key => $message)
	echo '<div class="alert alert-' . $key . '">' . $message . "</div>\n";

?>
<input id="baseUrl" type="hidden" value="<?=Url::base()?>/sales/indent">
<?php $form = ActiveForm::begin(['id' => 'application_form']); ?>
<?php 
// $form = ActiveForm::begin(['id' => 'application_form', 'enableClientValidation'=>false, 'layout' => 'horizontal',
//     'fieldConfig' => [
//         'template' => "{label}\n{beginWrapper}\n{input}\n{hint}\n{error}\n{endWrapper}",
//         'horizontalCssClasses' => [
//             'label' => 'col-sm-2',
//             'offset' => 'col-sm-offset-1',
//             'wrapper' => 'col-sm-10',
//             'error' => '',
//             'hint' => '',
//         ],
//     ],]); 
?>
<?php echo $form->errorSummary(array($model));?>
<section class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-content">
                <div class="card-body padding-bottom-zero">
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="row">
                                <div class="col-lg-2">
                                    <label>Tanggal</label>
                                </div>
                                <div class="col-lg-10">
                                    <?= $form->field($model, 'indent_date')->textInput(['class' => 'form-control input-sm date-inputmask', "data-inputmask" =>"'alias': 'datetime','inputFormat': 'dd-mm-yyyy'"])->label(false)?>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="row">
                                <div class="col-lg-2">
                                    <label>Customer</label>
                                </div>
                                <div class="col-lg-10">
                                    <?= $form->field($model, 'customer_id')
                                        ->dropDownList(ArrayHelper::map(customer::find()->where('is_deleted=:is', [':is' => 0])->all(),'id','customerName' ), ['class' => 'form-control input-sm select2', 'prompt' => 'pilih customer'])->label(false);
                                    ?>
                                </div>
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
                <div class="card-body padding-top-zero">
                    <div class="row">
                        <div class="col-lg-4 ">
                                <label><?= (new IndentItem)->getAttributeLabel('brand_name')?></label>
                                <?= Html::dropDownList('', '', ArrayHelper::map(Brand::find()->where('brand_type=:type', [':type' => 'ppi'])->andWhere('is_deleted=:is', [':is' => 0])->all(),'id','brand_name'), ['class' => 'form-control input-sm select2', 'prompt' => 'Pilih Merek', 'id' => 'select-brand']) ?>
                        </div>
                        <div class="col-lg-4">
                                <label><?= (new IndentItem)->getAttributeLabel('category_name')?></label>
                                <?= Html::dropDownList('', '', array(''), ['class' => 'form-control input-sm select2', 'prompt' => 'Pilih Kategori', 'id' => 'select-category']) ?>
                        </div>
                        <div class="col-lg-4">
                                <label>Barang</label>
                                <?= Html::dropDownList('', '', array(''), ['class' => 'form-control input-sm select2', 'prompt' => 'Pilih Barang', 'id' => 'select-product']) ?>
                        </div>
                    </div>
                    <br>
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="row-template" style="display: none">
                                <table class="table-template">
                                    <tr id="row-{index}" class="row-item">
                                        <td><center><span class="brand-name"></span></center></td>
                                        <td><center><span class="category-name"></span></center></td>
                                        <td><span class="product-name"></span></td>
                                        <td>
                                            <?=Html::hiddenInput('item[{index}][product_id]', '', array('class' => 'form-control product-id'));?>
                                            <?=Html::textInput('item[{index}][indent_item_qty]', '', array('class' => 'form-control indent-item-qty angka input-sm'));?>
                                        </td>
                                        <td><a href="#" class="btn btn-outline-danger btn-sm btn-remove"><i class="la la-trash"></i></a></td>
                                    </tr>
                                </table>
                            </div>
                            <div class="table-responsive">
                                <table class="table table-striped table-bordered table-item">
                                    <thead>
                                        <tr>
                                            <th style="width: 15%"><center><?= (new IndentItem)->getAttributeLabel('brand_name')?></center></th>
                                            <th style="width: 20%"><center><?= (new IndentItem)->getAttributeLabel('category_name')?></center></th>
                                            <th style="width: 50%;"><center><?= (new IndentItem)->getAttributeLabel('product_name')?></center></th>
                                            <th style="width: 10%;"><center><?= (new IndentItem)->getAttributeLabel('qty')?></center></th>
                                            <th style="width: 5%;"></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    <?php if (!empty($items)):?>
                                        <?php foreach($items as $i => $item):?>
                                            <?php if ($i !== "{index}"):?>
                                        <tr id="row-<?=$i?>" class="row-item">
                                            <td><center><span class="brand-name"><?= isset($item['brand_name']) ? $item['brand_name'] : '' ?></span></center></td>
                                            <td><center><span class="category-name"><?= isset($item['category_name']) ? $item['category_name'] : '' ?></span></center></td>
                                            <td><span class="product-name"><?= isset($item['product_name']) ? $item['product_name'] : '' ?></span></td>
                                            <td>
                                                <?=Html::hiddenInput('item['.$i.'][product_id]', $item['product_id'], array('class' => 'form-control product-id'));?>
                                                <?=Html::textInput('item['.$i.'][indent_item_qty]', $item['indent_item_qty'], array('class' => 'form-control indent-item-qty angka input-sm'));?>
                                            </td>
                                            <td><a href="#" class="btn btn-outline-danger btn-sm btn-remove"><i class="la la-trash"></i></a></td>
                                        </tr>
                                            <?php endif;?>
                                        <?php endforeach;?>
                                    <?php endif;?>
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
<script type="text/javascript">
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
function addRow(brand, category, product, id)
{
    var index = 0;
    if (typeof $(".table-item .row-item").last().attr("id") != 'undefined')
        index = parseInt($(".table-item .row-item").last().attr("id").split("-")[1]) + 1;
        
    var clone = $(".table-template .row-item").clone().attr("id", "row-"+index);
    
    clone.html(function(i, oldTr) {return oldTr.replace(/\{index}/g, index);});
    
    $(".table-item").append(clone);

    var tr = $(".table-item").find("#row-"+index);
    tr.find(".brand-name").html(brand);
    tr.find(".category-name").html(category);
    tr.find(".product-name").html(product);
    tr.find(".product-id").val(id);
}

$(document).on('click', '.btn-remove', function(e){

    var tr = $(this).closest('tr');
    tr.remove();
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
            
            addRow(data.brand_name, data.category_name, data.product_name, data.id);
        });
    }
});

// $(document).ready(function(){
//     var date = $('.date').val();
// });
</script>