<?php 
namespace common\components;

use Yii;
use yii\base\Component; 

class ErrorGenerateComponent extends Component
{
	public static function generateErrorLabels($arrOfErrors)
	{
		$string = "<ul>";
		foreach($arrOfErrors as $errors) {
			foreach($errors as $error) {
				$string .= "<li>".$error."</li>";
			}
		}
		$string .= "</ul>";
		return $string;
	}
}