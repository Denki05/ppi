<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use yii\widgets\ActiveForm;
use frontend\components\LabelComponent;
use app\components\BaseController;
use common\models\AuthItem;
use common\models\Store;

/* @var $this yii\web\View */
/* @var $model app\models\Project */
/* @var $form yii\widgets\ActiveForm */

$this->title = BaseController::getCustomPageTitle(BaseController::$page_caption);

$toolbar[] = '<button href="javascript:;" onclick="submitbutton(\'application.apply\')" type="button" class="btn btn-info btn-sm btn-right btn-form"><i class="fa fa-save"></i> ' . LabelComponent::SAVE_BUTTON . '</button>';
	
BaseController::$toolbar = $toolbar;
	
foreach(Yii::$app->session->getAllFlashes() as $key => $message)
	echo '<div class="alert alert-' . $key . '">' . $message . "</div>\n";

$form = ActiveForm::begin(['id' => 'application_form']);
echo $form->errorSummary([$model]);
?>
<div class="row">
	<div class="col-lg-12">
		<div class="ibox float-e-margins">
			<div class="ibox-title">
				<h5>Detil</h5>
				<div class="ibox-tools">
					<a class="collapse-link">
						<i class="fa fa-chevron-up"></i>
					</a>
				</div>
			</div>
			<div class="ibox-content">
				<?= $form->field($model, 'employee_name')->textInput();?>
				<?= $form->field($model, 'employee_address')->textArea(['rows' => '4']);?>
				<?= $form->field($model, 'employee_phone')->textInput();?>
				<?= $form->field($model, 'employee_mobile_phone')->textInput();?>
			</div>
		</div>
	</div>
</div>
<?php ActiveForm::end(); ?>