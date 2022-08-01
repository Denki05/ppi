<?php
namespace app\components;

use Yii;
use yii\web\Controller;
use yii\filters\AccessControl;

class BaseController extends Controller
{
	public static $page_caption = '';
	public static $page_title = '';
	public static $site_name = 'PPI';
	public static $toolbar = array();
	
    public function init()
    {
		parent::init();
    }
	
	public static function getCustomPageTitle($title = '')
	{
		$prefix = self::$site_name;

		if (trim($title) == '')
			$title = $prefix;
		else {
			if (is_array($prefix) && count($prefix) >= 1)
				$title = $prefix . ' - ' . $title;
		}
		return self::$page_title = $title;
	}
	
	public static function setPageCaption($pageCaption)
	{
		self::$page_caption = $pageCaption;
	}
	
	public static function addPermissionsInAuthItem()
	{
		
	}
	
	public function behaviors()
	{
		return [
			'access' => [
				'class' => AccessControl::className(),
				'rules' => [
					[
						'allow' => true,
						'actions' => [Yii::$app->controller->action->id],
						'roles' => [Yii::$app->controller->module->id.'.'.Yii::$app->controller->id.'.'.Yii::$app->controller->action->id, 'Super Administrator'],
					],
				],
			],
		];
	}
}
?>