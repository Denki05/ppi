<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use frontend\components\LabelComponent;
use app\components\BaseController;

$this->title = BaseController::getCustomPageTitle(BaseController::$page_caption);

$toolbar[] = '<button href="javascript:;" onclick="submitbutton(\'application.apply\')" type="button" class="btn btn-info btn-sm btn-right"><i class="fa fa-save"></i> ' . LabelComponent::SAVE_BUTTON . '</button>';
    
BaseController::$toolbar = $toolbar;
    
foreach(Yii::$app->session->getAllFlashes() as $key => $message)
    echo '<div class="alert alert-' . $key . '">' . $message . "</div>\n";

?>
<div class="row">
    <div class="col-lg-12">
        <?php $form = ActiveForm::begin(['id' => 'application_form']); ?>
        <?php echo $form->errorSummary($modelUser);?>
        <div class="ibox float-e-margins">
            <div class="ibox-content">
                <?= $form->field($modelUser, 'oldPassword')->passwordInput() ?>
                <?= $form->field($modelUser, 'newPassword')->passwordInput() ?>
                <?= $form->field($modelUser, 'retypePassword')->passwordInput() ?>
            </div>
        </div>
        <?php ActiveForm::end(); ?>
    </div>
</div>