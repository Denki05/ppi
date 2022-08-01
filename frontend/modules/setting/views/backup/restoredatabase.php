<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use kartik\form\ActiveForm;
use frontend\components\LabelComponent;
use app\components\BaseController;

$this->title = BaseController::getCustomPageTitle(BaseController::$page_caption);

$toolbar[] = '<button href="javascript:;" onclick="window.location.href = \''.Url::base().'/'.Yii::$app->controller->module->id.'/'.Yii::$app->controller->id.'/index\';" type="button" class="btn btn-default btn-sm dim"><i class="fa fa-reply"></i> '.LabelComponent::BACK_BUTTON.'</button>';
$toolbar[] = '<button href="javascript:;" onclick="submitbutton(\'application.apply\')" type="button" class="btn btn-primary btn-sm dim"><i class="fa fa-repeat	"></i> Restore</button>';

BaseController::$toolbar = $toolbar;

foreach(Yii::$app->session->getAllFlashes() as $key => $message)
	echo '<div class="alert alert-' . $key . '">' . $message . "</div>\n";
?>

<div class="row">
	<div class="col-lg-12">
		<div class="alert alert-danger">
			<h2><i>PENTING!! MOHON UNTUK MEMBUAT DAN MENDOWNLOAD BACKUP DATA KOMPUTER INI TERLEBIH DAHULU SEBELUM MELAKUKAN RESTORE!</i></h2>
		</div>
		<?php $form = ActiveForm::begin(['id' => 'application_form', 'options' => ['enctype' => "multipart/form-data"]]); ?>
		<?=$form->errorSummary($model)?>
		<div class="ibox float-e-margins">
			<div class="ibox-title">
				<h5>Restore</h5>
				<div class="ibox-tools">
					<a class="collapse-link">
						<i class="fa fa-chevron-up"></i>
					</a>
				</div>
			</div>
			<div class="ibox-content">
				<?= $form->field($model, 'upload_file')->fileInput(['accept' => '.sql'])->label('Pilih database');?>
			</div>
		</div>
		<?php ActiveForm::end();?>
	</div>
</div>
<script type="text/javascript">
function importButton(mode)
{
	$("#mode").val(mode);
	submitbutton('application.apply');
}
</script>