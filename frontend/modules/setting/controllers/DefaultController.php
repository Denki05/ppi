<?php
namespace frontend\modules\setting\controllers;

use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\ForbiddenHttpException;
use app\components\BaseController;
use frontend\components\LabelComponent;
use frontend\components\AccessComponent;
use frontend\models\SettingSearch;
use common\models\SettingCategory;
use common\models\Setting;

class DefaultController extends BaseController
{
	public function actionIndex()
	{
		BaseController::$page_caption = 'Setting';
		
		$searchModel = new SettingSearch();
		$model=new Setting;
		/*
		if (isset($_FILES['settings'])) {
			foreach($_FILES['settings']['name'] as $settingName => $value) {
				$setting = Setting::model()->find('setting_name=:name', array(':name' => $settingName));
				$setting->image_file = CUploadedFile::getInstanceByName('settings['.$settingName.']');
				if (!empty($setting->image_file)) {
					$extension = "jpg";
					$filename = "";
					if (($pos = strrpos($setting->image_file, '.')) !== FALSE) {
						$extension = substr($setting->image_file, $pos + 1);
						$filename = substr($setting->image_file, 0, $pos)."_".strtotime("now");
					}
					if (!file_exists("uploads") and !is_dir("uploads"))
						mkdir("uploads", 0777, TRUE);
					
					$setting->image_file->saveAs("uploads/" . $filename.".".$extension, false);
					$setting->setting_value = "uploads/" . $filename.".".$extension;
					$setting->save();
				}
			}
		}
		*/
		if (isset($_POST['settings'])) {
			foreach($_POST['settings'] as $settingName => $settingValue) {
				$setting = Setting::find()->andWhere('setting_name=:name', array(':name' => $settingName))->one();
				// print_r($setting);
				// exit();
				if ($setting) {
					if ($setting->setting_input_type == Setting::INPUT_TYPE_TEXTAREA)
						$settingValue = nl2br($settingValue);
					$setting->setting_value = $settingValue;
					if (!$setting->save()) {
						echo "<pre>".print_r($setting->getErrors(), 1)."</pre>";die();
					}
				}
			}
			Yii::$app->session->setFlash('success', 'Setting berhasil diupdate');
			return $this->redirect(array('index'));
		}
		
		$categoryTabs = array();
		$tabs = array();
		$categories = SettingCategory::find()->andWhere('is_deleted=:is', array(':is' => '0'))->all();
		foreach($categories as $i => $category) {
			$content = "";
			$modSetting = Setting::find()->andWhere('setting_category_id=:cat', [':cat' => $category->id])->all();
			if ($modSetting != array()) {
				$content = '<table border="0" class="table table-condensed table-striped td-middle">';
				foreach($modSetting as $set) {
					$content .= "<tr>";
					$content .= "<td style='width: 20%;'>".$set->setting_label."</td><td>";
					if ($set->setting_input_type == Setting::INPUT_TYPE_TEXT)
						$content .= "<input type='text' name='settings[".$set->setting_name."]' value='".$set->setting_value."' class='form-control'/>". (!empty($set->setting_desc) ? "<div class='help-block'>".$set->setting_desc."</div>" : "");
					else if ($set->setting_input_type == Setting::INPUT_TYPE_TEXTAREA)
						$content .= "<textarea name='settings[".$set->setting_name."]' rows='4' class='form-control'>".strip_tags($set->setting_value)."</textarea>". (!empty($set->setting_desc) ? "<div class='help-block'>".$set->setting_desc."</div>" : "");
					else if ($set->setting_input_type == Setting::INPUT_TYPE_FILE) {
						$content .= "<input type='file' name='settings[".$set->setting_name."]' class='form-control'/>". (!empty($set->setting_desc) ? "<div class='help-block'>".$set->setting_desc."</div>" : "");
						if (!empty($set->setting_value)) 
							$content .= "<br/><img src='".Url::base()."/uploads/".$set->setting_value."'/>";
					}
					else if ($set->setting_input_type == Setting::INPUT_TYPE_DROPDOWN) {
						$content .= "<select name='settings[".$set->setting_name."]' class='form-control input-".$set->setting_input_size."'>";
						if ($set->setting_value == "")
							$content .= "<option value=''>Pilih</option>";
						$values = explode(";", $set->setting_dropdown_options);
						foreach($values as $value) {
							$content .= "<option value='".$value."' ".($value == $set->setting_value ? "selected" : "").">".$value."</option>";
						}
						$content .= "</select>". (!empty($set->setting_desc) ? "<div class='help-block'>".$set->setting_desc."</div>" : "");
					}
					$content .= "</td></tr>";
				}
				$content .= "</table>";
			}
			
			$categoryTabs[] = array(
				'label' => $category->category_name,
				'content' => $content,
				'active' => $i <= 0 ? TRUE : FALSE
			);
		}

		return $this->render('index',array(
			'tabs' => $tabs,
			'categories' => $categories,
			'categoryTabs' => $categoryTabs,
		));
	}
	public function loadModel($id)
	{
		$model = Setting::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'Setelan tersebut tidak ada');
		return $model;
	}
}
