<?php
namespace common\components;

use Yii;
use yii\base\Component;

class DeliveryCostComponent extends Component 
{
	public static function getCountriesJSON($id='')
	{
		$curl = curl_init();

		curl_setopt_array($curl, array(
			CURLOPT_URL => "http://pro.rajaongkir.com/api/v2/internationalDestination?id=".$id,
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_ENCODING => "",
			CURLOPT_MAXREDIRS => 10,
			CURLOPT_TIMEOUT => 30,
			CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
			CURLOPT_CUSTOMREQUEST => "GET",
			CURLOPT_HTTPHEADER => array(
				"key: 4c03bbc02c4abbf2d78982bce275b1ec"
			),
		));

		$response = curl_exec($curl);
		$err = curl_error($curl);

		curl_close($curl);

		if ($err) {
			return "cURL Error #:" . $err;
		} else {
			return $response;
		}
	}
	
	public static function getCountries($id='')
	{
		$obj = json_decode(self::getCountriesJSON($id));
		if ($obj->rajaongkir->status->code != "400") {
			if (empty($id)) {
				$arr[0] = "Indonesia";
				foreach($obj->rajaongkir->results as $result) {
					$arr[$result->country_id] = $result->country_name;
				}
				return $arr;
			}
			else {
				return $obj->rajaongkir->results->country_name;
			}
		}
		else {
			return array();
		}
	}
	
	public static function getProvincesJSON($id='')
	{
		$curl = curl_init();

		$url = empty($id) ? "http://api.rajaongkir.com/starter/province" : "http://api.rajaongkir.com/starter/province?id=".$id;

		curl_setopt_array($curl, array(
			CURLOPT_URL => $url,
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_ENCODING => "",
			CURLOPT_MAXREDIRS => 10,
			CURLOPT_TIMEOUT => 30,
			CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
			CURLOPT_CUSTOMREQUEST => "GET",
			CURLOPT_HTTPHEADER => array(
				"key: 4c03bbc02c4abbf2d78982bce275b1ec"
			),
		));

		$response = curl_exec($curl);
		$err = curl_error($curl);

		curl_close($curl);

		if ($err) {
			return "cURL Error #:" . $err;
		} else {
			return $response;
		}
	}

	public static function getProvinces($id='')
	{
		$obj = json_decode(self::getProvincesJSON($id));
		if ($obj->rajaongkir->status->code != "400") {
			if (empty($id)) {
				$arr = array();
				foreach($obj->rajaongkir->results as $result) {
					$arr[$result->province_id] = $result->province;
				}
				return $arr;
			}
			else {
				return $obj->rajaongkir->results->province;
			}
		}
		else {
			return array();
		}
	}

	public static function getCitiesJSON($provinceId='', $id='')
	{
		$curl = curl_init();

		$url = "http://pro.rajaongkir.com/api/city";
		if (!empty($provinceId) || !empty($id)) {
			$url .= "?";
			if (!empty($provinceId) && empty($id))
				$url .= "province=".$provinceId;
			if (!empty($id) && empty($provinceId))
				$url .= "id=".$id;
			else if (!empty($id) && !empty($provinceId))
				$url .= "province=".$provinceId."&id=".$id;
		}

		curl_setopt_array($curl, array(
			CURLOPT_URL => $url,
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_ENCODING => "",
			CURLOPT_MAXREDIRS => 10,
			CURLOPT_TIMEOUT => 30,
			CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
			CURLOPT_CUSTOMREQUEST => "GET",
			CURLOPT_HTTPHEADER => array(
				"key: 4c03bbc02c4abbf2d78982bce275b1ec"
			),
		));

		$response = curl_exec($curl);
		$err = curl_error($curl);

		curl_close($curl);

		if ($err) {
			return "cURL Error #:" . $err;
		} else {
			return $response;
		}
	}

	public static function getCities($provinceId='', $id='')
	{
		$obj = json_decode(self::getCitiesJSON($provinceId, $id));
		if ($obj->rajaongkir->status->code != "400") {
			if (is_array($obj->rajaongkir->results)) {
				$arr = array();
				foreach($obj->rajaongkir->results as $result) {
					$arr[$result->city_id] = $result->city_name;
				}
				return $arr;
			}
			else {
				return $obj->rajaongkir->results->city_name;
			}
		}
		else
			return array();
	}

	public static function getSubdistrictJSON($cityId='', $id='')
	{
		$curl = curl_init();

		$url = "http://pro.rajaongkir.com/api/subdistrict";
		if (!empty($cityId) || !empty($id)) {
			$url .= "?";
			if (!empty($cityId) && empty($id))
				$url .= "city=".$cityId;
			if (!empty($id) && empty($cityId))
				$url .= "id=".$id;
			else if (!empty($id) && !empty($cityId))
				$url .= "city=".$cityId."&id=".$id;
		}

		curl_setopt_array($curl, array(
			CURLOPT_URL => $url,
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_ENCODING => "",
			CURLOPT_MAXREDIRS => 10,
			CURLOPT_TIMEOUT => 30,
			CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
			CURLOPT_CUSTOMREQUEST => "GET",
			CURLOPT_HTTPHEADER => array(
				"key: 4c03bbc02c4abbf2d78982bce275b1ec"
			),
		));

		$response = curl_exec($curl);
		$err = curl_error($curl);

		curl_close($curl);

		if ($err) {
			return "cURL Error #:" . $err;
		} else {
			return $response;
		}
	}

	public static function getSubdistrict($cityId='', $id='')
	{
		$obj = json_decode(self::getSubdistrictJSON($cityId, $id));
		if ($obj->rajaongkir->status->code != "400") {
			if (is_array($obj->rajaongkir->results)) {
				$arr = array();
				foreach($obj->rajaongkir->results as $result) {
					$arr[$result->subdistrict_id] = $result->subdistrict_name;
				}
				return $arr;
			}
			else {
				return $obj->rajaongkir->results->subdistrict_name;
			}
		}
		else
			return array();
	}

	public static function getDeliveryFeeJSON($destination, $weight)
	{
		$curl = curl_init();

		curl_setopt_array($curl, array(
			CURLOPT_URL => "http://pro.rajaongkir.com/api/cost",
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_ENCODING => "",
			CURLOPT_MAXREDIRS => 10,
			CURLOPT_TIMEOUT => 30,
			CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
			CURLOPT_CUSTOMREQUEST => "POST",
			CURLOPT_POSTFIELDS => "origin=151&originType=city&destination=".$destination."&destinationType=subdistrict&weight=".$weight."&courier=pos",
			CURLOPT_HTTPHEADER => array(
				"content-type: application/x-www-form-urlencoded",
				"key: 4c03bbc02c4abbf2d78982bce275b1ec"
			),
		));

		$response = curl_exec($curl);
		$err = curl_error($curl);

		curl_close($curl);

		if ($err) {
			return "cURL Error #:" . $err;
		} else {
			return $response;
		}
	}

	public static function getDeliveryFee($destination, $weight)
	{
		$obj = json_decode(self::getDeliveryFeeJSON($destination, $weight));
		$fee = 20;
		if ($obj->rajaongkir->status->code != "400") {
			foreach($obj->rajaongkir->results[0]->costs as $i => $costs) {
				if ($costs->service == self::DEFAULT_DELIVERY) {
					$fee = $costs->cost[0]->value;
					break;
				}
			}
		}
		return $fee;
	}

	public static function getArrayOfDeliveryCosts($destination, $weight)
	{
		$obj = json_decode(self::getDeliveryFeeJSON($destination, $weight));
		$arr = array();
		if ($obj->rajaongkir->status->code != "400") {
			foreach($obj->rajaongkir->results[0]->costs as $i => $costs) {
				$arr[$i] = array(
					'service' => $costs->service,
					'cost' => $costs->cost[0]->value
				);
			}
		}
		return $arr;
	}
}