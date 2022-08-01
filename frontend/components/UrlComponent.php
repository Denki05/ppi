<?php 
namespace frontend\components;

use Yii;
use yii\base\Component;

class UrlComponent extends Component
{
	public static function getUrlString($modelSearchName)
	{
        $urlString = "";
		if (isset($_SESSION[$modelSearchName]) && !empty($_SESSION[$modelSearchName])) {
            $i = 0;
            foreach($_SESSION[$modelSearchName] as $k => $v) {
                if ($i == 0)
                    $urlString .= "?";
                $urlString .= $modelSearchName."[".$k."]=".$v;
                if ($i < count($_SESSION[$modelSearchName]) - 1)
                    $urlString .= "&";
                $i++;
            }
            if (isset($_SESSION['page']))
                $urlString .= "&page=".$_SESSION['page'];
        }
        else {
            if (isset($_SESSION['page']) && !empty($_SESSION['page']))
                $urlString .= "?page=".$_SESSION['page'];
        }
        return $urlString;
	}
}