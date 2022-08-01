<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \common\models\LoginForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\helpers\Url;

$this->title = 'Login';

?>
<div class="card-content">
    <div class="card-body">
        <?php
            foreach(Yii::$app->session->getAllFlashes() as $key => $message)
            echo '<div class="alert alert-' . $key . ' mb-2" role="alert">' . $message . "</div>\n";
        ?>
        <?php $form = ActiveForm::begin(['id' => 'login-form']);?>
        <?php echo $form->errorSummary($model); ?>
            <div class="row">
                <div class="col-lg-12">
                    <fieldset class="form-group position-relative has-icon-left mb-0">
                        <input type="text" class="form-control" name="LoginForm[username]" placeholder="Your Username" required>
                        <div class="form-control-position">
                            <i class="ft-user"></i>
                        </div>
                    </fieldset>
                    <br>
                    <fieldset class="form-group position-relative has-icon-left">
                        <input type="password" class="form-control" name="LoginForm[password]" placeholder="Enter Password" required>
                        <div class="form-control-position">
                            <i class="la la-key"></i>
                        </div>
                    </fieldset>
                </div>
            </div>
            <br>
            <button type="submit" class="btn btn-info btn-block"><i class="ft-unlock"></i> Login</button>
        <?php ActiveForm::end();?>
    </div>
</div>