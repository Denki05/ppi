<?php
$params = array_merge(
    require(__DIR__ . '/../../common/config/params.php'),
    require(__DIR__ . '/../../common/config/params-local.php'),
    require(__DIR__ . '/params.php'),
    require(__DIR__ . '/params-local.php')
);

date_default_timezone_set("Asia/Bangkok");

return [
    'id' => 'app-frontend',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'controllerNamespace' => 'frontend\controllers',
    'language' => 'id',
    'modules' => [
        'comissiontype' => [
            'class' => 'frontend\modules\comissiontype\Comissiontype',
        ],
        'comissionpayrule' => [
            'class' => 'frontend\modules\comissionpayrule\Comissionpayrule',
        ],
        'customer' => [
            'class' => 'frontend\modules\customer\Customer',
        ],
        'employee' => [
            'class' => 'frontend\modules\employee\Employee',
        ],
        'product' => [
            'class' => 'frontend\modules\product\Product',
        ],
        'sales' => [
            'class' => 'frontend\modules\sales\Sales',
        ],
        'purchase' => [
            'class' => 'frontend\modules\purchase\Purchase',
        ],
        'setting' => [
            'class' => 'frontend\modules\setting\Setting',
        ],
        'report' => [
            'class' => 'frontend\modules\report\Report',
        ]
    ],
    'components' => [
        'assetManager' => [
            'linkAssets' => false,
            'bundles' => [
                'yii\web\JqueryAsset' => [
                    'jsOptions' => [ 'position' => \yii\web\View::POS_HEAD ],
                ],
                'yii\bootstrap\BootstrapAsset' => [
                    'css' => [],
                ],
            ],
            'forceCopy' => true,
        ],
        'request' => [
            'cookieValidationKey' => '[gfhjghsdjks44fdf4fgf4fgfg5645ggxcvvc]',
            'csrfParam' => '_csrf-frontend',
            'class' => 'common\components\Request',
            'web'=> '/frontend/web'
        ],
        'user' => [
            'identityClass' => 'common\models\User',
            'identityCookie' => ['name' => '_identity-frontend', 'httpOnly' => true],
            'loginUrl' => ['login']
        ],
        'session' => [
            // this is the name of the session cookie used for login on the frontend
            'name' => 'advanced-frontend',
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'rules' => [
                'login' => 'site/login',
                'myprofile' => 'setting/user/myprofile',
                'changepassword' => 'setting/user/changepassword',
            ],
        ],
        'authManager'=> [
            'class'=>'yii\rbac\DbManager'
        ],
        
    ],
    'params' => $params,
];
