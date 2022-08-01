<?php 
namespace common\components;

use Yii;
use common\models\Product;
use yii\base\Component; 

class CurrencyComponent extends Component
{
	public static function formatMoney($amount, $decimals = 0, $decPoint = ",", $thousandSep = ".", $currency=Product::CURRENCY_RUPIAH) 
	{
		if ($currency == Product::CURRENCY_DOLAR && self::is_decimal($amount))
			$decimals = 2;
		return Product::getCurrencyLabel($currency).number_format($amount, $decimals, $decPoint, $thousandSep);
	}

	public static function formatMoney2($amount, $decimals = 0, $decPoint = ",", $thousandSep = ".", $currency=Product::CURRENCY_RUPIAH) 
	{
		if ($currency == Product::CURRENCY_DOLAR && self::is_decimal($amount))
			$decimals = 2;
		return $currency.number_format($amount, $decimals, $decPoint, $thousandSep);
	}

	public static function is_decimal( $val )
	{
    	return is_numeric( $val ) && floor( $val ) != $val;
	}
}