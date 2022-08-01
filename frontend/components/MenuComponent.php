<?php 
namespace frontend\components;

use Yii;
use yii\base\Component;
use common\models\User;

class MenuComponent extends Component{

	public static function getArrOfMenu($currentController = '', $currentAction = '', $currentModule = '')
	{
		return array(
			array(
				'label' => 'Dashboard',
				'url' => 'dashboard',
				'icon' => 'mbri-home',
				'active' => $currentController == 'dashboard' ? true : false,
				'visible' => true,
			),
			array(
				'label' => 'Master Data',
				'url' => '#',
				'icon' => 'mbri-extension',
				'active' => in_array($currentModule, array('customer', 'comissiontype', 'comissionpayrule', 'product', 'employee')) ? true : false,
				'visible' => true,
				'items' => array(
					[
						'label' => 'Customer',
						'url' => 'customer',
						'icon' => 'la la-street-view',
						'active' => $currentModule == 'customer' ? true : false,
						'visible' => true,
					],
					[
						'label' => 'Tipe Komisi',
						'url' => 'comissiontype',
						'icon' => 'la la-barcode',
						'active' => $currentModule == 'comissiontype' ? true : false,
						'visible' => true,
					],
					// [
					// 	'label' => 'Aturan Pencairan Komosi',
					// 	'url' => 'comissionpayrule',
					// 	'icon' => 'la la-calculator',
					// 	'active' => $currentModule == 'comissionpayrule' ? true : false,
					// 	'visible' => true,
					// ],
					[
						'label' => 'Barang',
						'url' => 'product',
						'icon' => 'la la-tags	',
						'active' => $currentModule == 'product' ? true : false,
						'visible' => true,
					],
					[
						'label' => 'Karyawan',
						'url' => 'employee',
						'icon' => 'la la-users',
						'active' => $currentModule == 'employee' ? true : false,
						'visible' => true
					]
				)
			),
			array(
				'label' => 'Penjualan',
				'url' => '#',
				'icon' => 'mbri-cash',
				'active' => in_array($currentModule, array('indent', 'salesinvoice', 'salespayment')) ? true : false,
				'visible' => true,
				'items' => array(
					[
						'label' => 'Indent',
						'url' => 'sales/indent',
						'icon' => 'la la-dropbox',
						'active' => $currentModule == 'sales' && $currentController == 'indent' ? true : false,
						'visible' => true
					],
					[
						'label' => 'Nota',
						'url' => 'sales/salesinvoice',
						'icon' => 'la la-paste',
						'active' => $currentModule == 'sales' && $currentController == 'salesinvoice' ? true : false,
						'visible' => true,
						'items' => array(
							[
								'label' => 'Nota Biasa',
								'url' => 'sales/salesinvoicespecial',
								'active' => $currentModule == 'sales' && $currentController == 'salesinvoicespecial' ? true : false,
								'visible' => true,
							],
							[
								'label' => 'Nota Khusus',
								'url' => 'sales/salesinvoice',
								'active' => $currentModule == 'sales' && $currentController == 'salesinvoice' ? true : false,
								'visible' => true,
							],
							[
								'label' => 'Nota PPN',
								'url' => 'sales/salesinvoiceppn',
								'active' => $currentModule == 'sales' && $currentController == 'salesinvoiceppn' ? true : false,
								'visible' => true,
							],
						),
					],
					[
						'label' => 'Pembayaran Nota',
						'url' => 'sales/salespayment',
						'icon' => 'la la-money',
						'active' => $currentModule == 'sales' && $currentController == 'salespayment' ? true : false,
						'visible' => true
					],
					[
						'label' => 'Pencairan Komisi',
						'url' => 'sales/comissionpay',
						'icon' => 'la la-tint',
						'active' => $currentModule == 'sales' && $currentController == 'comissionpay' ? true : false,
						'visible' => true
					],
				),
			),
			array(
				'label' => 'Pembelian',
				'url' => '#',
				'icon' => 'mbri-shopping-cart',
				'active' => in_array($currentModule, array('purchaseorder', 'purchasepayment')) ? true : false,
				'visible' => true,
				'items' => array(
					[
						'label' => 'Purchase Order',
						'url' => 'purchase/purchaseorder',
						'icon' => 'la la-shopping-cart',
						'active' => $currentModule == 'purchase' && $currentController == 'purchaseorder' ? true : false,
						'visible' => true
					],
					[
						'label' => 'Pembayaran PO',
						'url' => 'purchase/purchasepayment',
						'icon' => 'la la-money',
						'active' => $currentModule == 'purchase' && $currentController == 'purchasepayment' ? true : false,
						'visible' => true
					]
				)
			),
			array(
				'label' => 'Setting',
				'url' => '#',
				'icon' => 'mbri-setting',
				'active' => in_array($currentModule, array('setting')) ? true : false,
				'visible' => true,
				'items' => array(
					[
						'label' => 'Profil Saya',
						'url' => 'setting/profile/myprofile',
						'icon' => 'la la-user',
						'active' => $currentModule == 'setting' && $currentController == 'profile' ? true : false,
						'visible' => true
					],
					[
						'label' => 'Setting',
						'url' => 'setting',
						'icon' => 'la la-gear',
						'active' => $currentModule == 'setting' && $currentController == 'default' ? true : false,
						'visible' => true
					],
					[
						'label' => 'Peran',
						'url' => 'setting/roles',
						'icon' => 'la la-list-ul',
						'active' => $currentModule == 'setting' && $currentController == 'role' ? true : false,
						'visible' => true
					],
					[
						'label' => 'User',
						'url' => 'setting/user',
						'icon' => 'la la-users',
						'active' => $currentModule == 'setting' && $currentController == 'user' ? true : false,
						'visible' => true,
					],
					[
						'label' => 'Backup',
						'url' => 'setting/backup',
						'icon' => 'la la-floppy-o',
						'active' => $currentModule == 'setting' && $currentController == 'backup' ? true : false,
						'visible' => true,
					],
					[
						'label' => 'Logout',
						'url' => 'site/logout',
						'icon' => 'la la-sign-out',
						'visible' => true,
					],
				),
			),
			array(
				'label' => 'Laporan',
				'url' => '#',
				'icon' => 'mbri-file',
				'active' => in_array($currentModule, array('report')) ? true : false,
				'visible' => true,
				'items' => array(
					[
						'label' => 'Laporan Komisi Sales',
						'url' => 'report/default',
						'icon' => 'la la-check-square',
						'active' => $currentModule == 'report' && $currentController == 'default' ? true : false,
						'visible' => true
					]
				),
			),
		);
	}
}