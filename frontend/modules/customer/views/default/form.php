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
use common\models\Store;

$this->title = BaseController::getCustomPageTitle(BaseController::$page_caption);

$toolbar[] = ButtonComponent::getBackButton();
$toolbar[] = ButtonComponent::getSaveButton();
	
BaseController::$toolbar = $toolbar;
	
foreach(Yii::$app->session->getAllFlashes() as $key => $message)
	echo '<div class="alert alert-' . $key . '">' . $message . "</div>\n";

?>
<?php $form = ActiveForm::begin(['id' => 'application_form']); ?>
<?php echo Html::hiddenInput('saveandnew', '0', ['id' => 'hiddensaveandnew']);?>
<?php //echo $form->errorSummary(array($model));?>

<section class="row">
    <div class="col-lg-4">
        <div class="card">
            <div class="card-content">
                <div class="card-body">
                    <?php if($mode == 'update'): ?>
                    <?= $form->field($model, 'customer_store_code')->textInput(['class'=>'form-control input-sm', 'readonly' => true]) ?>
                    <?php endif; ?>
                	<?= $form->field($model, 'customer_store_name')->textInput(['class'=>'form-control input-sm']) ?>
                    <?= $form->field($model, 'customer_owner_name')->textInput(['class'=>'form-control input-sm']) ?>
                    <?= $form->field($model, 'customer_zone')->textInput(['class'=>'form-control input-sm']) ?>
                    <?= $form->field($model, 'customer_province')->textInput(['class'=>'form-control input-sm']) ?>
                    <?= $form->field($model, 'customer_city')->textInput(['class'=>'form-control input-sm']) ?>
                    <?= $form->field($model, 'customer_store_postal_code')->textInput(['class'=>'form-control input-sm']) ?>
                    <?= $form->field($model, 'customer_store_address')->textArea(['class'=>'form-control']) ?>
                    
                </div>
            </div>
        </div>
    </div>
     <div class="col-lg-4">
        <div class="card">
            <div class="card-content">
                <div class="card-body">
                    <?= $form->field($model, 'customer_type')
                        ->dropDownList($model->getCustomerType(), ['empty' => LabelComponent::CHOOSE_DROPDOWN, 'class' => 'form-control input-sm']);
                    ?>
                    <?= $form->field($model, 'customer_has_tempo')->checkbox(['class' => 'i-checks'])->label('Memiliki Tempo?', ['class' => 'icheck-bold-label']) ?>
                    <?= $form->field($model, 'customer_tempo_limit')->textInput(['class'=>'form-control input-sm angka']) ?>
                    <?= $form->field($model, 'customer_credit_limit')->textInput(['class'=>'form-control input-sm angka']) ?>
                    <?= $form->field($model, 'customer_bank_name')->textInput(['class'=>'form-control input-sm']) ?>
                    <?= $form->field($model, 'customer_bank_acc_number')->textInput(['class'=>'form-control input-sm']) ?>
                    <?= $form->field($model, 'customer_bank_acc_name')->textInput(['class'=>'form-control input-sm']) ?>
                    <?= $form->field($model, 'customer_has_ppn')->checkbox(['class' => 'i-checks'])->label('Dikenakan PPN?', ['class' => 'icheck-bold-label']) ?>
                    <?= $form->field($model, 'customer_free_shipping')->checkbox(['class' => 'i-checks'])->label('Customer Free Shipping?', ['class' => 'icheck-bold-label']) ?>
                    
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="card">
            <div class="card-content">
                <div class="card-body">
                    <?= $form->field($model, 'customer_birthday')->textInput(['class'=>'form-control input-sm', 'id' => 'animate']) ?>
                    <?= $form->field($model, 'customer_phone1')->textInput(['class'=>'form-control input-sm']) ?>
                    <?= $form->field($model, 'customer_phone2')->textInput(['class'=>'form-control input-sm']) ?>
                    <?= $form->field($model, 'customer_identity_card_number')->textInput(['class'=>'form-control input-sm']) ?>
                    <?= $form->field($model, 'customer_identity_card_image')->fileInput() ?>
                    <?php if(isset($model->customer_identity_card_image)): ?>
                        <?= Html::a('<span  class="">'.$model->customer_identity_card_image.'</span>', Url::base(true).'/uploads/customers/'.$model->customer_identity_card_image, ['target' => '_blank' ]) ?>
                    <?php endif; ?>
                    <?= $form->field($model, 'customer_npwp')->textInput(['class'=>'form-control input-sm']) ?>
                    <?= $form->field($model, 'customer_npwp_image')->fileInput() ?>
                    <?php if(isset($model->customer_npwp_image)): ?>
                        <?= Html::a('<span  class="">'.$model->customer_npwp_image.'</span>', Url::base(true).'/uploads/customers/'.$model->customer_npwp_image, ['target' => '_blank' ]) ?>
                    <?php endif; ?>
                    <?= $form->field($model, 'customer_note')->textArea(['class'=>'form-control']) ?>
                </div>
            </div>
        </div>
    </div>
</section>
<?php ActiveForm::end(); ?>
<script>

$('#customer-customer_has_tempo').on('ifChecked',function(){
    $('#customer-customer_tempo_limit').val("14");
    $('#customer-customer_tempo_limit').attr('readonly', false);

    $('#customer-customer_credit_limit').val("0");
    $('#customer-customer_credit_limit').attr('readonly', false);    
})
.on('ifUnchecked',function(){
    $('#customer-customer_tempo_limit').val("");
    $('#customer-customer_tempo_limit').attr('readonly', true);
    
    $('#customer-customer_credit_limit').val("");
    $('#customer-customer_credit_limit').attr('readonly', true);
});

$(document).ready(function(){
    if ($('#customer-customer_has_tempo:checked').length > 0){
        
    }else{
        $('#customer-customer_tempo_limit').val("");
        $('#customer-customer_tempo_limit').attr('readonly', true);

        $('#customer-customer_credit_limit').val("");
        $('#customer-customer_credit_limit').attr('readonly', true);
    }
});

</script>