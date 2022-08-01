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
use common\models\AuthItem;

$this->title = BaseController::getCustomPageTitle(BaseController::$page_caption);

$toolbar[] = ButtonComponent::getBackButton();
$toolbar[] = ButtonComponent::getSaveButton();
	
BaseController::$toolbar = $toolbar;
	
foreach(Yii::$app->session->getAllFlashes() as $key => $message)
	echo '<div class="alert alert-' . $key . '">' . $message . "</div>\n";

?>
<?php $form = ActiveForm::begin(['id' => 'application_form']); ?>
<?php //echo $form->errorSummary(array($model));?>

<section class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-content">
            	<div class="card-header">
                    <h4 class="card-title">Detil</h4>
                    <a class="heading-elements-toggle"><i class="la la-ellipsis-v font-medium-3"></i></a>
                    <div class="heading-elements">
                        <ul class="list-inline mb-0">
                            <li><a data-action="collapse"><i class="ft-minus"></i></a></li>
                        </ul>
                    </div>
                </div>
                <div class="card-body padding-top-zero">
                	<?= $form->field($model, 'name')->textInput(['maxlength' => true, 'class' => 'form-control input-sm']) ?>
					<?= $form->field($model, 'description')->textInput(['maxlength' => true, 'class' => 'form-control input-sm']) ?>
                </div>
            </div>
        </div>
    </div>
</section>
<section class="row">
    <div class="col-12">
        <div class="card">
        	<div class="card-header">
                <h4 class="card-title">Hak Akses</h4>
                <a class="heading-elements-toggle"><i class="la la-ellipsis-v font-medium-3"></i></a>
                <div class="heading-elements">
                    <ul class="list-inline mb-0">
                        <li><a data-action="collapse"><i class="ft-minus"></i></a></li>
                    </ul>
                </div>
            </div>
            <div class="card-content">
                <div class="card-body padding-top-zero">
                	<?php foreach($accesses as $access):?>
						<fieldset class="access-item">
							<legend><?=$access['label']?></legend>
							<hr>
							<?=Html::checkboxlist("AuthItem[access]", $model->access, $access['value']);?>
						</fieldset>
					<?php endforeach;?>
			     </div>
            </div>
        </div>
    </div>
</section>
<?php ActiveForm::end(); ?>
