<?php

/* @var $this \yii\web\View */
/* @var $content string */

use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use frontend\assets\AppAsset;
use common\widgets\Alert;
use yii\helpers\Url;
use app\components\BaseController;

AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?php $this->registerCsrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <script type="text/javascript">
        var base = "<?=Yii::$app->request->getAbsoluteUrl();?>";
        var base2 = "<?=Url::base();?>";
        var base3 = "<?=Url::base(true);?>";
    </script>
    <?php \yii\bootstrap\BootstrapPluginAsset::register($this); ?>
    <?php $this->head() ?>
</head>
<body class="vertical-layout vertical-compact-menu 2-columns   fixed-navbar" data-open="click" data-menu="vertical-compact-menu" data-col="2-columns">
<?php $this->beginBody() ?>
    <nav class="header-navbar navbar-expand-md navbar navbar-with-menu navbar-without-dd-arrow fixed-top navbar-light navbar-shadow navbar-brand-center"
    >
        <div class="navbar-wrapper">
            <div class="navbar-header">
                <ul class="nav navbar-nav flex-row">
                    <li class="nav-item mobile-menu d-md-none mr-auto"><a class="nav-link nav-menu-main menu-toggle hidden-xs" href="#"><i class="ft-menu font-large-1"></i></a></li>
                    <li class="nav-item " >
                        <h3 class="header-caption"><b>Premium Parfum Indonesia</b></h3>
                        <h3 class="header-caption-small"><b>PPI</b></h3>
                    </li>
                    <li class="nav-item d-md-none"><a class="nav-link open-navbar-container" data-toggle="collapse" data-target="#navbar-mobile"><i class="la la-ellipsis-v"></i></a></li>
                </ul>
            </div>
            <div class="navbar-container content">
                <div class="collapse navbar-collapse" id="navbar-mobile">
                    <ul class="nav navbar-nav mr-auto float-left">
                        <li class="nav-item d-none d-md-block"><a class="nav-link nav-menu-main menu-toggle " href="#"><i class="ft-menu"></i></a></li>
                        <li class="nav-item d-none d-lg-block"><a class="nav-link nav-link-expand" href="#"><i class="ficon ft-maximize"></i></a></li>
                        <?php if (!empty(BaseController::$page_caption)):?>
                        <li class="nav-item d-none d-md-block ml-1 left-margin">
                            <h3 class="content-header-title mb-0 header-caption"><?php echo BaseController::$page_caption;?></h3>
                        </li>
                        <?php endif;?>

                    </ul>
                    <?php if (!empty(BaseController::$page_caption)):?>
                    <ul class="nav navbar-nav float-right">
                        <li class="dropdown dropdown-user nav-item">
                            <?php
                            if (!empty(BaseController::$toolbar)) {
                                foreach (BaseController::$toolbar as $toolbar) {
                                    echo $toolbar . "&nbsp;";
                                }
                            }
                        ?>
                        </li>
                    </ul>
                    <?php endif;?>
                </div>
            </div>
        </div>
    </nav>
    
<?php echo Yii::$app->view->render('part/sidebar'); ?><!-- NAV BAR -->
    <div class="app-content content">
        <div class="content-wrapper">
            <div class="content-body">

            <?php echo $content; ?> 
            </div>       
        </div>
    </div>
    <!-- END: Content-->

    <div class="sidenav-overlay"></div>
    <div class="drag-target"></div>

    <!-- BEGIN: Footer-->
    <footer class="footer footer-static footer-light navbar-border navbar-shadow">
        <p class="clearfix blue-grey lighten-2 text-sm-center mb-0 px-2"><span class="float-md-left d-block d-md-inline-block">Copyright &copy; 2019 <a class="text-bold-800 grey darken-2" >Premium Parfum Indonesia</a></span><span class="float-md-right d-none d-lg-block"> <span id="scroll-top"></span></span></p>
    </footer>
    <!-- END: Footer-->

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
