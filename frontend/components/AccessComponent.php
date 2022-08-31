<?php 
namespace frontend\components;

use Yii;
use yii\base\Component;
use common\models\AuthItem;

class AccessComponent extends Component
{
	public static function hasAccess($module, $controller, $action)
	{
		if ($module && $controller && $action)
		{
			$module = str_replace('/', '.', $module);
			$operation = $module . '.' . $controller . '.' . $action;
			
			return (array_key_exists('Super Administrator', Yii::$app->authManager->getRolesByUser(Yii::$app->user->id)) || \Yii::$app->user->can($operation));
		}
		return false;
	}
	
	public static function getArrOfAccess()
	{
		return [
			'customer' => [
				'label' => 'Customer',
				'value' => (new AuthItem)->getPermissionsByModule('customer')
			],
			'comissiontype' => [
				'label' => 'Tipe Komisi', 
				'value' => (new AuthItem)->getPermissionsByModule('comissiontype'),
			],
			'product' => [
				'label' => 'Barang',
				'value' => (new AuthItem)->getPermissionsByModule('product'),
			],
			'productarchive' => [
				'label' => 'Barang Arsip',
				'value' => (new AuthItem)->getPermissionsByModule('productarchive'),
			],
			'employee' => [
				'label' => 'Pegawai',
				'value' => (new AuthItem)->getPermissionsByModule('employee'),
			],
			'sales.indent' => [
				'label' => 'Indent',
				'value' => (new AuthItem)->getPermissionsByModule('sales.indent')
			],
			'sales.salesinvoicespecial' => [
				'label' => 'Nota',
				'value' => (new AuthItem)->getPermissionsByModule('sales.salesinvoicespecial'),
			],
			'sales.salesinvoice' => [
				'label' => 'Nota Khusus',
				'value' => (new AuthItem)->getPermissionsByModule('sales.salesinvoice'),
			],
			'sales.salespayment' => [
				'label' => 'Pembayaran Nota',
				'value' => (new AuthItem)->getPermissionsByModule('sales.salespayment'),
			],
			'sales.comissionpay' => [
				'label' => 'Pencairan Komisi',
				'value' => (new AuthItem)->getPermissionsByModule('sales.comissionpay'),
			],
			'purchase.purchaseorder' => [
				'label' => 'Purchase Order',
				'value' => (new AuthItem)->getPermissionsByModule('purchase.purchaseorder'),
			],
			'purchase.purchasepayment' => [
				'label' => 'Pembayaran PO',
				'value' => (new AuthItem)->getPermissionsByModule('purchase.purchasepayment'),
			],
			'report' => [
				'label' => 'Laporan',
				'value' => (new AuthItem)->getPermissionsByModule('report'),
			],
			'setting' => [
				'label' => 'Setting',
				'value' => (new AuthItem)->getPermissionsByModule('setting'),
			],
		];
	}
}