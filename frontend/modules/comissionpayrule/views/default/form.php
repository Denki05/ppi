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
    <div class="col-12">
        <div class="card">
            <div class="card-content">
                <div class="card-body">
                	<?= $form->field($model, 'comission_pay_rule_start_day')->textInput(['class'=>'form-control input-sm']) ?>
                    <?= $form->field($model, 'comission_pay_rule_end_day')->textInput(['class'=>'form-control input-sm']) ?>
                    <?= $form->field($model, 'comission_pay_rule_value')->textInput(['class'=>'form-control angka input-sm']) ?>
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

</script>