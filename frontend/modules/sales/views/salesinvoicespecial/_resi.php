<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\widgets\Pjax;
use yii\bootstrap\Modal;
use yii\helpers\Url;
use yii\db\ActiveRecord;
?>

<?php $form = ActiveForm::begin(); ?>

<h2 align="center" style="font-weight: bold"> Update Cost Resi <p style="color:red;">#<?= $model->invoice_code ?> <p></h2> 
<div class="box box-info">
    <div class="box-body">

        <div class="form-group">
            <?= $form->field($model, 'invoice_cost_resi')->textInput(['class'=>'form-control input-sm']) ?>
        </div>

    </div>
    <div class="box-footer">
        <?= Html::submitButton('Save', ['class' => 'btn btn-info pull-right']) ?>
    </div>

</div>