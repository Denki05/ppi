<?php 
namespace common\components;

use Yii;
use yii\base\Component; 

class DateComponent extends Component{

	public static function getHumanizedDate($date = NULL, $is_long = TRUE, $with_time = FALSE, $remove_year = FALSE, $remove_date = FALSE, $pm_am = TRUE)
	{
		if ($date == NULL) {
			$date = date('Y-m-d H:i:s');
		}

		if ($is_long === TRUE) {
			if ($with_time === TRUE) {
				if ($pm_am) {
					return date('d M Y, g:i A', strtotime($date));
				} else {
					return date('d F Y H:i:s', strtotime($date));
				}
			} else {
				$format = 'd F Y';
				if ($remove_year == TRUE) {
					$format = 'd F';
				} else {
					if ($remove_date) {
						$format = 'F Y';
					}
				}

				return date($format, strtotime($date));
			}
		} else {
			if ($with_time === TRUE) {
				if ($pm_am) {
					return date('d M Y, g:i A', strtotime($date));
				} else {
					return date('d F Y H:i:s', strtotime($date));
				}
			} else {
				$format = 'd-m-Y';
				if ($remove_year == TRUE) {
					$format = 'd-m';
				} else {
					if ($remove_date) {
						$format = 'm-Y';
					}
				}

				return date($format, strtotime($date));
			}
		}

		return $date;
	}
	
	public static function getHumanizedDateTime($date = NULL, $is_long = TRUE)
	{
		return self::getHumanizedDate($date, $is_long, TRUE);
	}
	
	public static function getArrOfDays()
	{
		$days = array();
		for($i=1; $i<=31; $i++) {
			$days[$i] = $i;
		}
		return $days;
	}
	
	public static function getArrOfMonth($month = '')
	{
		$arrOfMonth = array('1' => 'Januari', '2' => 'Febuari', '3' => 'Maret', '4' => 'April', '5' => 'Mei', '6' => 'Juni', '7' => 'Juli', '8' => 'Agustus', '9' => 'September', '10' => 'Oktober', '11' => 'November', '12' => 'Desember');
		return empty($month) ? $arrOfMonth : $arrOfMonth[$month];
	}
	
	public static function getYears($min, $max)
	{
		$arr = array();
		for($i=$min; $i<=$max; $i++) {
			$arr[$i] = $i;
		}
		return $arr;
	}
}