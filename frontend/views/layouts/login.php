<?php

/* @var $this \yii\web\View */
/* @var $content string */

use frontend\assets\AppAsset;
use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use yii\helpers\Url;
use common\widgets\Alert;

AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<body  class="vertical-layout vertical-compact-menu 1-column   blank-page" data-open="click" data-menu="vertical-compact-menu" data-col="1-column">
	<?php $this->beginBody() ?>
	<div class="app-content content">
        <div class="content-wrapper">
            <div class="content-header row mb-1">
            </div>
		<div class="content-body">
	                <section class="flexbox-container">
	                    <div class="col-12 d-flex align-items-center justify-content-center">
	                        <div class="col-lg-4 col-md-8 col-10 box-shadow-2 p-0">
	                            <div class="card border-grey border-lighten-3 m-0">
	                                <div class="card-header border-0">
	                                    <div class="card-title text-center">
	                                        <div class="p-1"><strong><h2>Premium Parfum Indonesia</h2></strong></div>
	                                    </div>
	                                </div>
	                                <?= $content; ?>
	                            </div>
	                        </div>
	                    </div>
	                </section>

	            </div>
	        </div>
	    </div>

    <?php $this->endBody() ?>
</body>

</html>
<?php $this->endPage() ?>