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
use common\models\Factory;
use common\models\Searah;
use common\models\Brand;
use common\models\Category;

$this->title = BaseController::getCustomPageTitle(BaseController::$page_caption);

$toolbar[] = ButtonComponent::getBackButton();
$toolbar[] = ButtonComponent::getSaveButton();
	
BaseController::$toolbar = $toolbar;
	
foreach(Yii::$app->session->getAllFlashes() as $key => $message)
	echo '<div class="alert alert-' . $key . '">' . $message . "</div>\n";

?>
<input id="baseUrl" type="hidden" value="<?=Url::base()?>/product/default">
<?php $form = ActiveForm::begin(['id' => 'application_form']); ?>
<?php echo Html::hiddenInput('saveandnew', '0', ['id' => 'hiddensaveandnew']);?>
<?php //echo $form->errorSummary(array($model));?>

<section class="row">
    <div class="col-6">
        <div class="card">
            <div class="card-content">
                <div class="card-body">
                    <?= $form->field($model, 'product_is_new')->checkbox(['class' => 'checkicheck'])->label($model->getAttributeLabel('product_is_new'), ['class' => 'icheck-bold-label']) ?>
                	<?= $form->field($model, 'product_material_code')->textInput(['class'=>'form-control input-sm']) ?>
                    <?= $form->field($model, 'product_material_name')->textInput(['class'=>'form-control input-sm']) ?>
                    <?= $form->field($model, 'product_code')->textInput(['class'=>'form-control input-sm']) ?>
                    <?= $form->field($model, 'product_name')->textInput(['class'=>'form-control input-sm']) ?>
                    <?= $form->field($model, 'factory_id')
                        ->dropDownList( ArrayHelper::map(Factory::find()->all(),'id','factory_name' ), ['class' => 'selectize-select', 'prompt' => 'Pilih / Tambah Pabrik']
                        )?>
                    <?= $form->field($model, 'brand_id')
                        ->dropDownList( ArrayHelper::map(Brand::find()->where('brand_type=:type', [':type' => 'ppi'])->all(),'id','brand_name' ), ['class' => 'selectize-select', 'prompt' => 'Pilih / Tambah Merek']
                        )?>
                    <?= $form->field($model, 'category_id')
                        ->dropDownList( $mode === 'update' ? ArrayHelper::map(Category::find()->where('id=:id',[':id' => $model->category_id])->all(),'id','category_name' ) : array(), ['class' => 'form-control input-sm', 'prompt' => 'Pilih / Tambah Kategori', 'id' => 'category-id']
                        )?>
                </div>
            </div>
        </div>
    </div>
    <div class="col-6">
        <div class="card">
            <div class="card-content">
                <div class="card-body">
                    <?= $form->field($model, 'original_brand_id')
                        ->dropDownList( ArrayHelper::map(Brand::find()->where('brand_type=:type', [':type' => 'original'])->all(),'id','brand_name' ), ['class' => 'selectize-select', 'prompt' => 'Pilih / Tambah Original Merek']
                        )?>
                    <?= $form->field($model, 'searah_id')
                        ->dropDownList( ArrayHelper::map(Searah::find()->all(),'id','searah_name' ), ['class' => 'selectize-select', 'prompt' => 'Pilih / Tambah Searah']
                        )?>
                    <?= $form->field($model, 'product_gender')
                        ->dropDownList($model->getProductGender(), ['empty' => LabelComponent::CHOOSE_DROPDOWN, 'class' => 'form-control input-sm']);
                    ?>
                    
                    <div class="form-group position-relative has-icon-left product-form">
                        <label class="control-label" for="product-product_cost_price"><?= $model->getAttributeLabel('product_cost_price')?></label>
                        <input type="text" class="form-control form-control-sm input-sm angka invoice-disc-amount" name="Product[product_cost_price]" value="<?= isset($model->product_sell_price) ? $model->product_cost_price : ''?>" required>
                        <div class="form-control-position">
                            <i class=" secondary font-small-3">$</i>
                        </div>
                        <p class="help-block help-block-error"></p>
                    </div>
                    <div class="form-group position-relative has-icon-left product-form">
                        <label class="control-label" for="product-product_cost_price"><?= $model->getAttributeLabel('product_sell_price')?></label>
                        <input type="text" class="form-control form-control-sm input-sm angka invoice-disc-amount" name="Product[product_sell_price]" value="<?= isset($model->product_sell_price) ? $model->product_sell_price : ''?>" required>
                        <div class="form-control-position">
                            <i class=" secondary font-small-3">$</i>
                        </div>
                        <p class="help-block help-block-error"></p>
                    </div>
                    <?= $form->field($model, 'product_web_image')->textInput(['class'=>'form-control input-sm']) ?>
                    <?= $form->field($model, 'product_status')
                        ->dropDownList($model->getProductStatus(), ['empty' => LabelComponent::CHOOSE_DROPDOWN, 'class' => 'form-control input-sm']);
                    ?>
                    <?= $form->field($model, 'product_live')
                        ->dropDownList($model->getProductCondition(), ['empty' => LabelComponent::CHOOSE_DROPDOWN, 'class' => 'form-control input-sm']);
                    ?>
                    <?= $form->field($model, 'product_type')
                        ->dropDownList($model->getProductType(), ['empty' => LabelComponent::CHOOSE_DROPDOWN, 'class' => 'form-control input-sm', 'prompt' => 'Pilih Tipe Barang']);
                    ?>
                </div>
            </div>
        </div>
    </div>
</section>
<?php ActiveForm::end(); ?>
<script>
	$(document).on('click', 'saveandnew', function(){
		$('#hiddensaveandnew').val(1);
	});
    
    $(document).on("change", "#product-brand_id", function(e){
        var id = $(this).val();
        var url = $('#baseUrl').val();
        

        $('#category-id').removeClass("form-control");
        $('#category-id').removeClass("input-sm");
        
        $('#category-id').selectize()[0].selectize.destroy();
        
        $.get(url+'/getitem',{id:id},function(response){
            let data = $.parseJSON(response);
            
            $('#category-id').selectize({
                valueField: 'id',
                labelField: 'category_name',
                searchField: 'category_name',
                options: [],
                delimiter: ',',
                persist: false,
                create: true,
                onInitialize: function(){
                    var selectize = this;
                        selectize.clear();
                        selectize.addOption(data);
                        var selected_items = [];
                        // $.each(data, function( i, obj) {
                        //     selected_items.push(obj.id);
                        // });
                        // selectize.setValue(selected_items);
                }
            });
        });

    });
    
    $(document).ready(function(){
        // $('#category-id').selectize();
    });

</script>