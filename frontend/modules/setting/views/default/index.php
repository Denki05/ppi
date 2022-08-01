<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use common\models\Category;
use common\models\Unit;
use yii\widgets\ActiveForm;
//use kartik\form\ActiveForm;
use frontend\components\LabelComponent;
use app\components\BaseController;
use frontend\components\ButtonComponent;

$this->title = BaseController::getCustomPageTitle(BaseController::$page_caption);

$toolbar = array();
$toolbar[] = ButtonComponent::getSaveButton();
	
BaseController::$toolbar = $toolbar;
	
foreach(Yii::$app->session->getAllFlashes() as $key => $message)
	echo '<div class="alert alert-' . $key . '">' . $message . "</div>\n";

?>
<?php $form = ActiveForm::begin(['id' => 'application_form', 'options' => ['enctype' => 'multipart/form-data']]); ?>
<section class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-content">
                <div class="card-body">
					<ul class="nav nav-tabs nav-linetriangle no-hover-bg">
					<?php foreach($categoryTabs as $i => $tab):?>
						<li class="nav-item">
							<a class="nav-link <?php echo $tab['active'] ? "active" : "";?>" id="base-tab<?php echo $i;?>" data-toggle="tab" aria-controls="tab<?php echo $i;?>" href="#tab<?php echo $i;?>" aria-expanded="true"><?php echo $tab['label'];?></a>
						</li>
					<?php endforeach;?>
					</ul>
					<div class="tab-content pt-1">
					<?php foreach($categoryTabs as $i => $tab):?>
						<div role="tabpanel" class="tab-pane <?php echo $tab['active'] ? "active" : "";?>" id="tab<?php echo $i;?>" aria-expanded="true" aria-labelledby="base-tab<?php echo $i;?>">
							<p><?php echo $tab['content'];?></p>
						</div>
					<?php endforeach;?>
					</div>
				</div>
			</div>
		</div>
	</div>
</section>
<?php ActiveForm::end();?>