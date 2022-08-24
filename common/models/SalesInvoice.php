<?php

namespace common\models;

use Yii;
use common\components\ErrorGenerateComponent;
use frontend\components\PrintingHelper;
/**
 * This is the model class for table "tbl_sales_invoice".
 *
 * @property int $id
 * @property int $customer_id
 * @property int $salesman_id
 * @property int $comission_type_id
 * @property int $bank_id
 * @property string $invoice_date
 * @property string $invoice_code
 * @property string $invoice_code_ppn
 * @property string $invoice_subtotal
 * @property string $invoice_disc_amount
 * @property double $invoice_disc_percent
 * @property string $invoice_tax_amount
 * @property double $invoice_tax_percent
 * @property string $invoice_grand_total
 * @property string $invoice_outstanding_amount
 * @property string $invoice_status
 * @property string $invoice_payment_status
 * @property string $invoice_exchange_rate
 * @property string $invoice_comission_value
 * @property string $invoice_comission_pay_date
 * @property string $created_on
 * @property string $updated_on
 * @property int $created_by
 * @property int $updated_by
 * @property int $is_deleted
 *
 * @property TblDelivery[] $tblDeliveries
 * @property TblComissionType $comissionType
 * @property TblCustomer $customer
 * @property TblEmployee $salesman
 * @property TblBank $bank
 * @property TblSalesPayment[] $tblSalesPayments
 */
class SalesInvoice extends \common\models\MasterModel
{
    const STATUS_NEW = 'new';
    const STATUS_SENT = 'sent';
    const STATUS_CLOSE = 'close';
    const STATUS_PAYMENT_NEW = 'new';
    const STATUS_PAYMENT_PARTIAL = 'partial';
    const STATUS_PAYMENT_PAID = 'paid';
    const STATUS_INVOICE_PPN = 'ppn';
    // const STATUS_INVOICE_NON_PPN = 'nonppn';

    public $customer_name, $salesman_name, $payment_date, $bank_type, $bank_image;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'tbl_sales_invoice';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['customer_id', 'salesman_id', 'invoice_date', 'invoice_subtotal', 'invoice_grand_total', 'invoice_outstanding_amount'], 'required'],
            [['customer_id', 'salesman_id', 'comission_type_id', 'bank_id', 'created_by', 'updated_by', 'is_deleted'], 'integer'],
            [['invoice_date', 'invoice_comission_pay_date', 'created_on', 'updated_on', 'customer_name', 'salesman_name', 'invoice_receiver', 'invoice_destination_address', 'invoice_postal_code', 'invoice_destination_city', 'invoice_destination_province', 'payment_date', 'invoice_disc_percent2'], 'safe'],
            [['invoice_subtotal', 'invoice_disc_amount', 'invoice_disc_amount2', 'invoice_disc_percent', 'invoice_tax_amount', 'invoice_tax_percent', 'invoice_grand_total', 'invoice_outstanding_amount', 'invoice_exchange_rate', 'invoice_comission_value', 'invoice_shipping_cost'], 'number'],
            [['invoice_status', 'invoice_payment_status', 'invoice_type'], 'string'],
            [['invoice_code', 'invoice_code_ppn', 'invoice_number_document'], 'string', 'max' => 255],
            [['comission_type_id'], 'exist', 'skipOnError' => true, 'targetClass' => ComissionType::className(), 'targetAttribute' => ['comission_type_id' => 'id']],
            [['customer_id'], 'exist', 'skipOnError' => true, 'targetClass' => Customer::className(), 'targetAttribute' => ['customer_id' => 'id']],
            [['salesman_id'], 'exist', 'skipOnError' => true, 'targetClass' => Employee::className(), 'targetAttribute' => ['salesman_id' => 'id']],
            [['bank_id'], 'exist', 'skipOnError' => true, 'targetClass' => Bank::className(), 'targetAttribute' => ['bank_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'customer_id' => 'Customer',
            'salesman_id' => 'Sales',
            'comission_type_id' => 'Tipe Komisi',
            'bank_id' => 'Rekening Transfer',
            'invoice_date' => 'Tanggal Nota',
            'invoice_code' => 'Nomor Nota',
            "invoice_code_ppn" => 'Nomor Nota PPN',
            'invoice_subtotal' => 'Subtotal',
            'invoice_disc_amount' => 'Disc Tambahan',
            'invoice_disc_amount2' => 'Disc Tambahan 2',
            'invoice_disc_percent' => 'Invoice Disc Percent',
            'invoice_disc_percent2' => 'Invoice Disc Percent 2',
            'invoice_tax_amount' => 'Pajak',
            'invoice_tax_percent' => 'Invoice Tax Percent',
            'invoice_grand_total' => 'Total Akhir',
            'invoice_outstanding_amount' => 'Total Belum Terbayar',
            'invoice_number_document' => 'Nomer Dokumen',
            'invoice_status' => 'Status Nota',
            'invoice_payment_status' => 'Status Pembayaran',
            'invoice_type' => 'Type Invoice',
            'invoice_exchange_rate' => 'Nilai Kurs',
            'invoice_comission_value' => 'Jumlah Komisi',
            'invoice_comission_pay_date' => 'Tanggal Komisi',
            'salesman_name' => 'Sales',
            'customer_name' => 'Customer',
            'created_on' => 'Created On',
            'updated_on' => 'Updated On',
            'created_by' => 'Created By',
            'updated_by' => 'Updated By',
            'is_deleted' => 'Is Deleted',
            'invoice_receiver' => 'Penerima',
            "invoice_destination_address" => 'Alamat Kirim',
            "invoice_postal_code" => 'Kode Post',
            'invoice_destination_city' => 'Kota',
            'invoice_destination_province' => 'Provinsi',
            'payment_date' => 'Tanggal Bayar Terakhir',
            'invoice_shipping_cost' => 'Ongkos Kirim',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDeliveries()
    {
        return $this->hasMany(Delivery::className(), ['invoice_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getComissionType()
    {
        return $this->hasOne(ComissionType::className(), ['id' => 'comission_type_id']);
    }

     /**
     * @return \yii\db\ActiveQuery
     */
    public function getBankType()
    {
        return $this->hasOne(Bank::className(), ['id' => 'bank_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCustomer()
    {
        return $this->hasOne(Customer::className(), ['id' => 'customer_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSalesman()
    {
        return $this->hasOne(Employee::className(), ['id' => 'salesman_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSalesPayments()
    {
        return $this->hasMany(SalesPayment::className(), ['invoice_id' => 'id']);
    }

    public function getSalesInvoiceItems()
    {
        return $this->hasMany(SalesInvoiceItem::className(), ['invoice_id' => 'id']);
    }
    
    public function getStatusLabel($status='')
    {
        $statuses = array(
            self::STATUS_NEW => 'Baru',
            self::STATUS_SENT => 'Sent',
            self::STATUS_CLOSE => 'Tutup',
        );
        
        return $status == '' ? $statuses : (isset($statuses[$status]) ? $statuses[$status] : "");
    }

    public function getStatusPayment($status='')
    {
        $statuses = array(
            self::STATUS_PAYMENT_NEW => 'Baru',
            self::STATUS_PAYMENT_PARTIAL => 'Sebagian',
            self::STATUS_PAYMENT_PAID => 'Lunas',
        );
        
        return $status == '' ? $statuses : (isset($statuses[$status]) ? $statuses[$status] : "");
    }

    public function getStatusInvoice($status='')
    {
        $statuses = array(
            self::STATUS_INVOICE_PPN => 'PPN',
            // self::STATUS_INVOICE_NON_PPN => 'NONPPN'
        );
        
        return $status == '' ? $statuses : (isset($statuses[$status]) ? $statuses[$status] : "");
    }

    public function updatePaymentStatus($invoiceId)
    {
        $invoice = SalesInvoice::find()->andWhere('id=:id', [':id' => $invoiceId])->one();
            
        if ($invoice->invoice_outstanding_amount == 0){
            $invoice->invoice_payment_status = self::STATUS_PAYMENT_PAID;
            $invoice->invoice_status = self::STATUS_CLOSE;
        }
        else if ($invoice->invoice_outstanding_amount == $invoice->invoice_grand_total){
            $invoice->invoice_payment_status = self::STATUS_PAYMENT_NEW;
            $invoice->invoice_status = self::STATUS_NEW;
        }
        else if ($invoice->invoice_outstanding_amount < $invoice->invoice_grand_total && $invoice->invoice_outstanding_amount > 0){
            $invoice->invoice_payment_status = self::STATUS_PAYMENT_PARTIAL;
            $invoice->invoice_status = self::STATUS_NEW;
        }
        else{
            $invoice->invoice_payment_status = self::STATUS_PAYMENT_PAID;
            $invoice->invoice_status = self::STATUS_CLOSE;
        }

        if (!$invoice->save()) {
                return array(
                    'success' => false,
                    'message' => "Gagal mengupdate status pembayaran nota karena kesalahan berikut: ".ErrorGenerateComponent::generateErrorLabels($invoice->getErrors())
                );
            }
    
            return array(
                'success' => true,
                'message' => '',
            );
    }

    public function isPayment()
    {
        return SalesPayment::find()->andWhere('invoice_id=:id AND is_deleted=:is', [':id' => $this->id, ':is' => 0])->one() ? true : false;
    }

    public function createPO($items){
        
        $tempsubtotal = 0;
        foreach($items as $i => $item) {
            if ($i !== "{index}") {
                $tempsubtotal += ($item['invoice_item_qty'] * $item['invoice_item_price']) - $item['invoice_item_disc_amount'];
            }
        }
        $temptotal = $tempsubtotal - $this->invoice_disc_amount;

        $po = new PurchaseOrder();
        $po->purchase_order_code = $po->getLatestNumber('PO', 'purchase_order_code');
        $po->purchase_order_date = $this->invoice_date;
        $po->supplier_id = 1;
        $po->purchase_order_status = 'new';
        $po->purchase_order_subtotal = $tempsubtotal;
        $po->purchase_order_disc_percent = $this->invoice_disc_percent;
        $po->purchase_order_disc_amount = $this->invoice_disc_amount;
        $po->purchase_order_grand_total = $temptotal;

        if(!$po->save()){
            $errorString = ErrorGenerateComponent::generateErrorLabels($po->getErrors());
            return array('success' => false, 'message' => 'Gagal membuat PO karena kesalahan berikut: '.$errorString);
        }
        else{
            foreach($items as $i => $item) {
                if ($i !== "{index}") {
                    $temp = new PurchaseOrderItem;
                    $temp->purchase_order_id = $po->id;
                    $temp->product_id = $item['product_id'];
                    $temp->purchase_order_item_qty = $item['invoice_item_qty'];
                    $temp->purchase_order_item_disc_amount = $item['invoice_item_disc_amount'];
                    $temp->purchase_order_item_disc_percent = $item['invoice_item_disc_percent'];
                    $temp->purchase_order_item_price = $item['invoice_item_price'];
                    $temp->purchase_order_item_row_total = ($item['invoice_item_qty'] * $item['invoice_item_price']) - $item['invoice_item_disc_amount'];
                    if (!$temp->save()) {
                        $errorMessage = ErrorGenerateComponent::generateErrorLabels($temp->getErrors());
                        return array('success' => false, 'message' => 'Gagal membuat PO karena kesalahan berikut: '.$errorMessage);
                    }
                }
            }
        }
        return array('success' => true, 'message' => '');
    }

    public function getLastPayment(){
        $payment = SalesPayment::find()->where('invoice_id=:id AND is_deleted=:is', [':id' => $this->id, ':is' => 0])->orderBy(['id' => SORT_DESC])->one();
        if($payment)
            return $payment->payment_date;
        else
            return '';
    }

    public function getInvoiceContent()
    {
        $maxChar = 96;
        $marginLeft = 4;
        $maxRow = 11;

        $colHeaderRight = 25;
        $colProdCode = 18;
        $colProduct = 31;
        $colQty = 10;
        $colQtyKG = 10;
        $colPacking = 10;
        $colPackaging = 12;

        $boldStart = Chr(27) . Chr(69); 
        $boldEnd = Chr(27) . Chr(70);

        $contentFooter = "";

        $contentFooter .= PrintingHelper::LoopVar("Line",1);
        $contentFooter .= PrintingHelper::LoopVar('Space', $marginLeft+1)."    DIBUAT OLEH   ";
        $contentFooter .= "      GUDANG      ";
        $contentFooter .= "     PACKING      ";
        $contentFooter .= "      SOPIR       ";
        $contentFooter .= "     PENERIMA     ";
        $contentFooter .= PrintingHelper::LoopVar("Line",1);
        $contentFooter .= PrintingHelper::LoopVar("Line",1);
        $contentFooter .= PrintingHelper::LoopVar("Line",1);
        $contentFooter .= PrintingHelper::LoopVar('Space', $marginLeft+1)." (".PrintingHelper::LoopVar("Min",14).") ";
        $contentFooter .= " (".PrintingHelper::LoopVar("Min",14).") ";
        $contentFooter .= " (".PrintingHelper::LoopVar("Min",14).") ";
        $contentFooter .= " (".PrintingHelper::LoopVar("Min",14).") ";
        $contentFooter .= " (".PrintingHelper::LoopVar("Min",14).") ";

        $items = $this->salesInvoiceItems;
        $numOfPages = ceil(count($items)/$maxRow);
        
        $contentHeader = PrintingHelper::LoopVar('Space', floor(($maxChar - strlen("SURAT JALAN")*3)/2)).PrintingHelper::setFont('DoubleWide')."SURAT JALAN".PrintingHelper::setFont('SanSerif');
        $contentHeader .= PrintingHelper::LoopVar("Line",1);
        $contentHeader .= PrintingHelper::setFont('Elite').PrintingHelper::LoopVar('Space',$marginLeft).PrintingHelper::LoopVar("SD", $maxChar - $marginLeft);
        $contentHeader .= PrintingHelper::LoopVar("Line",1);
        $contentHeader .= PrintingHelper::LoopVar('Space',$marginLeft)."Kepada : ".$this->invoice_receiver.PrintingHelper::LoopVar('Space', $maxChar - $marginLeft - strlen("Kepada : ".$this->invoice_receiver) - $colHeaderRight)."No Srt Jalan : ".$this->invoice_code;
        $contentHeader .= PrintingHelper::LoopVar("Line",1);
        $contentHeader .= PrintingHelper::LoopVar('Space',$marginLeft)."Alamat : ".$this->invoice_destination_address.PrintingHelper::LoopVar('Space', $maxChar - $marginLeft - strlen("Alamat : ".$this->invoice_destination_address) - $colHeaderRight)."Tanggal      : ".date("d-m-Y", strtotime($this->invoice_date));
        $contentHeader .= PrintingHelper::LoopVar("Line",1);
        $contentHeader .= PrintingHelper::LoopVar('Space',$marginLeft + strlen("Alamat : ")).$this->invoice_destination_city;
        $contentHeader .= PrintingHelper::LoopVar("Line",1);
        if(!empty($this->customer->customer_phone1) || !empty($this->customer->customer_phone2)) {
            $arrPhone = array();
            if(!empty($this->customer->customer_phone1))
                array_push($arrPhone, $this->customer->customer_phone1);
            if(!empty($this->customer->customer_phone2))
                array_push($arrPhone, $this->customer->customer_phone2);
            $phoneString = implode(", ", $arrPhone);
            $contentHeader .= PrintingHelper::LoopVar('Space',$marginLeft)."Telp   : ".$phoneString;
            $contentHeader .= PrintingHelper::LoopVar("Line",1);
        }

        $contentBody = '';

        $itemIndex = 0;
        $countQtyAll = 0;
        for ($i = 1; $i <= $numOfPages; $i++) {
            $contentBody .= $contentHeader;
            $contentBody .= PrintingHelper::LoopVar('Space',$marginLeft).PrintingHelper::LoopVar("Min",$maxChar - $marginLeft);
            $contentBody .= PrintingHelper::LoopVar("Line",1);
            
            // ============== TABLE HEADER =================
            $contentBody .= PrintingHelper::LoopVar('Space',$marginLeft);
            $contentBody .= " KODE BARANG".PrintingHelper::LoopVar('Space', $colProdCode - strlen(" KODE BARANG"));
            $contentBody .= " NAMA BARANG".PrintingHelper::LoopVar('Space', $colProduct - strlen(" NAMA BARANG"));
            $contentBody .= "Qty(KG)".PrintingHelper::LoopVar('Space', $colQtyKG - strlen("QTY(KG)"));
            $contentBody .= "PACKING".PrintingHelper::LoopVar('Space', $colPacking - strlen("PACKING"));
            $contentBody .= "JUMLAH".PrintingHelper::LoopVar('Space', $colQty - strlen("JUMLAH"));
            $contentBody .= "KEMASAN".PrintingHelper::LoopVar('Space', $colPackaging - strlen("KEMASAN"));
            $contentBody .= PrintingHelper::LoopVar("Line",1);
            $contentBody .= PrintingHelper::LoopVar('Space',$marginLeft).PrintingHelper::LoopVar("Min",$maxChar - $marginLeft);
            $contentBody .= PrintingHelper::LoopVar("Line",1);
            // ============== TABLE HEADER END ===============
            
            // ============== TABLE BODY ===============
            $countQty = 0;
            for($j=$itemIndex; $j<($i*$maxRow); $j++) {        
                $contentBody .= PrintingHelper::LoopVar('Space',$marginLeft);
                $contentBody .= " ".$items[$itemIndex]->product->product_code.PrintingHelper::LoopVar('Space', $colProdCode - strlen(" ".$items[$itemIndex]->product->product_code));
                if (strlen(" ".$items[$itemIndex]->product->product_name) >= $colProduct)
                    $contentBody .= " ".substr($items[$itemIndex]->product->product_name, 0, $colProduct-1).PrintingHelper::LoopVar('Space', $colProduct - strlen(" ".substr($items[$itemIndex]->product->product_name, 0, $colProduct-1)));
                else
                    $contentBody .= " ".$items[$itemIndex]->product->product_name.PrintingHelper::LoopVar('Space', $colProduct - strlen(" ".$items[$itemIndex]->product->product_name));
                $contentBody .= $items[$itemIndex]->invoice_item_qty.PrintingHelper::LoopVar('Space', $colQtyKG - strlen($items[$itemIndex]->invoice_item_qty));
                $contentBody .= $items[$itemIndex]->packaging->packaging_value.PrintingHelper::LoopVar('Space', $colPacking - strlen($items[$itemIndex]->packaging->packaging_value));
                $contentBody .= ($items[$itemIndex]->invoice_item_qty/$items[$itemIndex]->packaging->packaging_value).PrintingHelper::LoopVar('Space', $colQty - strlen(($items[$itemIndex]->invoice_item_qty/$items[$itemIndex]->packaging->packaging_value)));
                $contentBody .= $items[$itemIndex]->packaging->packaging_packing.PrintingHelper::LoopVar('Space', $colPacking - strlen($items[$itemIndex]->packaging->packaging_packing));
                $contentBody .= PrintingHelper::LoopVar("Line",1);
                $itemIndex++;
                $countQty++;
                if ($itemIndex == count($items))
                    break;
            }
            // ============== TABLE BODY END ===============

            //tambah beberapa baris kosong bila jumlah barang kurang dari baris Maksimum hingga mencapai baris maksimum
            for($j = 0; $j < (($i*$maxRow) - count($items)); $j++) {
                $contentBody .= PrintingHelper::LoopVar("Line",1);
            }
            
            //totalqty
            $countQtyAll += $countQty;

            $contentBody .= PrintingHelper::LoopVar('Space',$marginLeft).PrintingHelper::LoopVar("Min",$maxChar - $marginLeft);
            $contentBody .= PrintingHelper::LoopVar("Line",1);
            // $contentBody .= PrintingHelper::LoopVar('Space',$marginLeft + ($colQty - strlen($countQty))).$countQty;
            // if($i == $numOfPages)
            //     $contentBody .= PrintingHelper::LoopVar('Space',$maxChar - ($marginLeft + $colQty +strlen('Qty Total '.$countQtyAll))).'Qty Total '.$countQtyAll;
            // $contentBody .= PrintingHelper::LoopVar("Line",1);
            
            if ($i < $numOfPages) {
                $contentBody .= chr(10).chr(12);
            }
        }
        
        $contentBody .= $contentFooter;

        return $contentBody;
    }
}
