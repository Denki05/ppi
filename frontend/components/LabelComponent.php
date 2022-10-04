<?php
namespace frontend\components;

use Yii;
use yii\base\Component; 

class LabelComponent extends Component
{
	const SAVE_BUTTON = 'Simpan';
	const BACK_BUTTON = 'Kembali';
	const NEW_BUTTON = 'Baru';
	const EDIT_BUTTON = 'Update';
	const GENERATE_PDF_BUTTON = 'Generate PDF';
	const VIEW_BUTTON = 'Lihat';
	const DELETE_BUTTON = 'Hapus';
	const CLOSE_BUTTON = 'Tutup';
	const PAY_BUTTON = 'Bayar';
	const PRINT_BUTTON = 'Print';
	const EXPORT_BUTTON = 'Export';
	const IMPORT_BUTTON = 'Import';
	const SEARCH_BUTTON = 'Cari';
	const EXCEL_BUTTON = 'Excel';
	const PDF_BUTTON = 'PDF';
	const HISTORY_BUTTON = 'History';
	const PAYMENT_BUTTON = 'Pembayaran';
	const SUCCESS_SAVE = 'berhasil disimpan!';
	const SUCCESS_UPDATE = 'berhasil diupdate!';
	const SUCCESS_DELETE = 'berhasil dihapus!';
	const SUCCESS_CHECK = 'Berhasil Check Product Type!';
	const ERROR_SAVE = 'gagal disimpan';
	const ERROR_DELETE = 'gagal dihapus';
	const NOT_FOUND = 'tidak ditemukan';
	const CHOOSE_DROPDOWN = 'Pilih';
	const ICON_ADD = 'plus';
	const ICON_UPDATE = 'pencil';
	const ICON_EDIT = 'edit';
	const ICON_DELETE = 'trash';
	const ICON_VIEW = 'eye-open';
	const ICON_DETAIL = 'search';
	const ICON_PRINT = 'print';
	const ICON_DOWNLOAD = 'download';
	const ICON_CHECK = 'check';
	const ICON_HISTORY = 'list';
	const MODE_CREATE = 'create';
	const MODE_UPDATE = 'update';
	const ICON_APPROVE = 'thumbs-up';
	const ICON_REJECT = 'thumbs-down';
	
	const GRID_SUMMARY = 'Menampilkan <b>{begin}-{end}</b> dari <b>{totalCount}</b> item';
}
?>