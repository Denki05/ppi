<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
// use yii\widgets\ActiveForm;
use yii\bootstrap\ActiveForm;
use frontend\components\LabelComponent;
use frontend\components\AccessComponent;
use app\components\BaseController;
use common\models\Factory;
use common\models\Searah;
use common\models\Brand;
use common\models\Category;

$this->title = BaseController::getCustomPageTitle(BaseController::$page_caption);
$toolbar[] = '<button href="javascript:;" onclick="window.location.href = \''.Url::base().'/'.Yii::$app->controller->module->id.'/'.Yii::$app->controller->id.'/index\';" type="button" class="btn btn-secondary btn-sm btn-glow"> '.LabelComponent::BACK_BUTTON.'</button>';
$toolbar[] = '<button href="javascript:;" onclick="importButton(\'import\')" type="button" class="btn btn-info btn-sm btn-glow"> '.LabelComponent::IMPORT_BUTTON.'</button>';
BaseController::$toolbar = $toolbar;
    
foreach(Yii::$app->session->getAllFlashes() as $key => $message)
    echo '<div class="alert alert-' . $key . '">' . $message . "</div>\n";

?>
<input id="baseUrl" type="hidden" value="<?=Url::base()?>/product/default">
<?php $form = ActiveForm::begin(['id' => 'application_form', 'options' => ['enctype' => "multipart/form-data"]]); ?>
<?php echo Html::hiddenInput('saveandnew', '0', ['id' => 'hiddensaveandnew']);?>
<?php //echo $form->errorSummary(array($model));?>

<section class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-content">
                <div class="card-body">
                    <div class="col-lg-12">
                        <?= $form->field($model, 'excelFile')->fileInput(['accept' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet']) ?>
                        <?= Html::hiddenInput('mode', '', array('id' => 'mode'));?>
                        <br>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<?php ActiveForm::end(); ?>
<script type="text/javascript">
function importButton(mode)
{
    $("#mode").val(mode);
    submitbutton('application.apply');
}
</script>