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
                <?= $form->field($model, 'bank_name')
                        ->dropDownList($model->getBankList(), ['empty' => LabelComponent::CHOOSE_DROPDOWN, 'class' => 'form-control input-sm']);
                    ?>
                    <?= $form->field($model, 'bank_type')
                        ->dropDownList($model->getBankType(), ['empty' => LabelComponent::CHOOSE_DROPDOWN, 'class' => 'form-control input-sm']);
                    ?>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-8">
        <div class="card">
            <div class="card-content">
                <div class="card-body">
                    <?= $form->field($model, 'bank_acc_name')->textInput(['class'=>'form-control input-sm']) ?>
                    <?= $form->field($model, 'bank_acc_number')->textInput(['class'=>'form-control input-sm']) ?>
                    <?= $form->field($model, 'bank_image')->fileInput() ?>
                    <?php if(isset($model->bank_image)): ?>
                        <?= Html::a('<span  class="">'.$model->bank_image.'</span>', Url::base(true).'/uploads/rekening/'.$model->bank_image, ['target' => '_blank' ]) ?>
                    <?php endif; ?>
                    <?= $form->field($model, 'bank_note')->textArea(['class'=>'form-control']) ?>
                </div>
            </div>
        </div>
    </div>
</section>
<?php ActiveForm::end(); ?>