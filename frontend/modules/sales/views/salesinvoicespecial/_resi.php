<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use app\components\BaseController;
?>
<?php $form = ActiveForm::begin(); ?>

<?php 

    $this->title = BaseController::getCustomPageTitle(BaseController::$page_caption);
    

    foreach(Yii::$app->session->getAllFlashes() as $key => $message)
	echo '<div class="alert alert-' . $key . '">' . $message . "</div>\n";

?>

<section class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-content">
                <div class="card-body padding-bottom-zero padding-top-zero">
                    <div class="row">
                        <div class="col-lg-12">
                           <?= $form->field($model, 'invoice_cost_resi')->textInput(['class'=>'form-control input-sm angka']) ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

    <div class="form-group">
        <?= Html::submitButton('Submit', ['class' => 'btn btn-primary']) ?>
    </div>

<?php ActiveForm::end(); ?>