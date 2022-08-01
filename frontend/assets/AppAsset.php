<?php

namespace frontend\assets;

use yii\web\AssetBundle;

/**
 * Main frontend application asset bundle.
 */
class AppAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        "css/font-sand.css",
        "css/vendors/css/vendors.min.css",
        "css/vendors/css/extensions/datedropper.min.css",
        "css/vendors/css/extensions/timedropper.min.css",
        "css/vendors/css/forms/selects/selectize.css",
        "css/vendors/css/forms/selects/selectize.default.css",
        "css/vendors/css/forms/selects/select2.min.css",
        "css/iCheck/custom.css",
        "css/bootstrap.css",
        "css/bootstrap-extended.css",
        "css/colors.css",
        "css/components.css",
        "css/core/menu/menu-types/vertical-compact-menu.css",
        "css/core/colors/palette-gradient.css",
        "css/fonts/mobiriseicons/24px/mobirise/style.css",
        "css/vendors/jquery-jvectormap-2.0.3.css",
        "css/vendors/morris.css",
        "css/fonts/simple-line-icons/style.css",
        "css/core/colors/palette-gradient.css",
        "css/login-register.css",
        "css/sweetalert.css",
        "css/style.css",
        "css/mystyle.css",
        "css/checkboxes-radios.css",
        "css/selectize.css",
        "css/form-extended.css",
    ];
    public $js = [
        "js/vendors/vendors.min.js",
        "js/vendors/validation/jqBootstrapValidation.js",
        "js/vendors/chart.min.js",
        "js/vendors/raphael-min.js",
        "js/vendors/morris.min.js",
        "js/vendors/jquery-jvectormap-2.0.3.min.js",
        "js/vendors/jquery-jvectormap-world-mill.js",
        "js/vendors/visitor-data.js",
        "js/core/app-menu.js",
        "js/core/app.js",
        'js/sweetalert.min.js',
        "js/myjs.js",
        "js/form-login-register.js",
        "js/vendors/extensions/datedropper.min.js",
        "js/vendors/extensions/timedropper.min.js",
        "js/scripts/extensions/date-time-dropper.js",
        "js/vendors/select/selectize.min.js",
        "js/core/libraries/jquery_ui/jquery-ui.min.js",
        "js/scripts/form-selectize.js",
        "js/vendors/select/select2.full.min.js",
        "js/scripts/form-select2.js",
        "js/vendors/jquery.inputmask.bundle.min.js",
        "js/scripts/form-inputmask.js",
        "js/iCheck/icheck.min.js",
    ];
    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
    ];
}
