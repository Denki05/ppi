<?php
namespace frontend\controllers;

use Yii;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use common\models\LoginForm;
use app\components\BaseController;

/**
 * Dashboard controller
 */
class DashboardController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
			
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['login', 'error'],
                        'allow' => true,
                    ],
                    [
                        'actions' => ['logout', 'index'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
        ];
    }

    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex()
    {
		/*
		$auth = Yii::$app->authManager;
		
		$createPost = $auth->createPermission('see-project-list');
        $createPost->description = 'Melihat daftar project';
        $auth->add($createPost);
		
		$author = $auth->createRole('author');
        $auth->add($author);
        $auth->addChild($author, $createPost);
		
		$auth->assign($author, 1);
		*/
		/*
		$auth = Yii::$app->authManager;
		
		$superAdmin = $auth->createRole('superadministrator');
        $auth->add($superAdmin);
        $superAdminRole = $auth->getRole('superadministrator');
		
		$createPost = $auth->createPermission('see-unit-list');
        $createPost->description = 'Melihat daftar Unit';
        $auth->add($createPost);
		$auth->addChild($superAdminRole, $createPost);
		
		$createPost = $auth->createPermission('see-client-list');
        $createPost->description = 'Melihat daftar client';
        $auth->add($createPost);
		$auth->addChild($superAdminRole, $createPost);
		
		$createPost = $auth->createPermission('see-employee-list');
        $createPost->description = 'Melihat daftar karyawan';
        $auth->add($createPost);
		$auth->addChild($superAdminRole, $createPost);
		
		$auth->assign($superAdminRole, 1);
		*/
		
		BaseController::$page_caption = 'Dashboard';
		
        return $this->render('index');
    }
}
