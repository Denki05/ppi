<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "tbl_customer".
 *
 * @property int $id
 * @property string $customer_store_code
 * @property string $customer_store_name
 * @property string $customer_zone
 * @property string $customer_province
 * @property string $customer_city
 * @property string $customer_type
 * @property int $customer_has_tempo
 * @property int $customer_tempo_limit number of days
 * @property string $customer_credit_limit
 * @property string $customer_identity_card_number
 * @property string $customer_identity_card_image
 * @property string $customer_npwp
 * @property string $customer_npwp_image
 * @property string $customer_bank_name
 * @property string $customer_bank_acc_number
 * @property string $customer_bank_acc_name
 * @property int $customer_has_ppn
 * @property int $customer_free_shipping
 * @property string $customer_status
 * @property string $customer_owner_name
 * @property string $customer_birthday
 * @property string $customer_phone1
 * @property string $customer_phone2
 * @property string $created_on
 * @property string $updated_on
 * @property int $created_by
 * @property int $updated_by
 * @property int $is_deleted
 *
 * @property TblIndent[] $tblIndents
 * @property TblSalesInvoice[] $tblSalesInvoices
 * @property TblSalesPayment[] $tblSalesPayments
 */
class Customer extends \common\models\MasterModel
{
    const CUSTOMER_TYPE_UMUM = '1100-umum(semuaProspek)';
    const CUSTOMER_TYPE_AGEN = '1110-agenPerfumeryTrusted';
    const CUSTOMER_TYPE_BIGRESELLER = '1111-bigReseller';
    const CUSTOMER_TYPE_SMRESELLER = '1112-smReseller';
    const CUSTOMER_TYPE_BIG_PERFUMERY = '1121-bigPerfumery(toko/multicabang)';
    const CUSTOMER_TYPE_SM_PERFUMERY = '1122-smPerfumery(toko/multicabang)';
    const CUSTOMER_TYPE_UMUM_PROJECT = '2100-umumProject(semuaProspek)';
    const CUSTOMER_TYPE_HOME_INDUSTRI_KOSMETIK = '2110-homeIndustriKosmetik';
    const CUSTOMER_TYPE_HOME_INDUSTRI_PKRT = '2111-homeIndustriPkrt';
    const CUSTOMER_TYPE_INDUSTRI_KOSEMTIK = '2120-industriKosmetik(ppn)';
    const CUSTOMER_TYPE_INDUSTRI_PKRT = '2121-industriPkrt(ppn)';
    const CUSTOMER_STATUS_ACTIVE = 'active';
    const CUSTOMER_STATUS_INACTIVE = 'inactive';
    public $imageCard, $imageNpwp;
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'tbl_customer';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['customer_store_code', 'customer_store_name', 'customer_store_address'], 'required'],
            [['customer_type', 'customer_status'], 'string'],
            [['customer_has_tempo', 'customer_tempo_limit', 'customer_has_ppn', 'customer_free_shipping', 'created_by', 'updated_by', 'is_deleted'], 'integer'],
            [['customer_credit_limit'], 'number'],
            [['imageCard', 'imageNpwp'],'file', 'extensions' => 'png,jpg,jpeg', 'maxSize' => 1024000, 'skipOnEmpty' => true],
            [['customer_birthday', 'created_on', 'updated_on', 'customer_note'], 'safe'],
            [['customer_store_code', 'customer_store_name', 'customer_zone', 'customer_province', 'customer_city', 'customer_identity_card_number', 'customer_identity_card_image', 'customer_npwp', 'customer_npwp_image', 'customer_bank_name', 'customer_bank_acc_number', 'customer_bank_acc_name', 'customer_owner_name', 'customer_phone1', 'customer_phone2', 'customer_store_postal_code', 'customer_store_address'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'customer_type_id' => 'Customer Type',
            'customer_store_code' => 'Kode Toko',
            'customer_store_name' => 'Nama Toko',
            'customer_zone' => 'Wilayah',
            'customer_province' => 'Provinsi',
            'customer_city' => 'Kota',
            'customer_type' => 'Tipe',
            'customer_has_tempo' => 'Tempo Customer',
            'customer_tempo_limit' => 'Batas Tempo (hari)',
            'customer_credit_limit' => 'Batas Hutang',
            'customer_identity_card_number' => 'No KTP',
            'customer_identity_card_image' => 'Foto KTP',
            'customer_npwp' => 'NPWP',
            'customer_npwp_image' => 'Foto NPWP',
            'customer_bank_name' => 'Bank',
            'customer_bank_acc_number' => 'Nomor Rekening',
            'customer_bank_acc_name' => 'Atas Nama',
            'customer_has_ppn' => 'PPN',
            'customer_free_shipping' => 'Free Shipping',
            'customer_status' => 'Status Customer',
            'customer_owner_name' => 'Nama Customer',
            'customer_birthday' => 'Tanggal Lahir',
            'customer_phone1' => 'Telepon 1',
            'customer_phone2' => 'Telepon 2',
            'created_on' => 'Created On',
            'updated_on' => 'Updated On',
            'created_by' => 'Created By',
            'updated_by' => 'Updated By',
            'is_deleted' => 'Is Deleted',
            'imageCard' => 'Upload ID Card',
            'imageNpwp' => 'Upload NPWP',
            'customer_store_postal_code' => 'Kode Post',
            'customer_store_address' => 'Alamat Toko',
            'customer_note' => 'Catatan',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getIndents()
    {
        return $this->hasMany(Indent::className(), ['customer_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSalesInvoices()
    {
        return $this->hasMany(SalesInvoice::className(), ['customer_Id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSalesPayments()
    {
        return $this->hasMany(SalesPayment::className(), ['customer_id' => 'id']);
    }

    public function getCustomerType($type='')
    {
        $types = [
            self::CUSTOMER_TYPE_UMUM  => 'Umum (Semua Prospek)',
            self::CUSTOMER_TYPE_AGEN => 'Agen Perfumery Trusted',
            self::CUSTOMER_TYPE_BIGRESELLER => 'BIG Reseller',
            self::CUSTOMER_TYPE_SMRESELLER => 'SM Reseller',
            self::CUSTOMER_TYPE_BIG_PERFUMERY => 'BIG Perfumery(Toko/Multicabang)',
            self::CUSTOMER_TYPE_SM_PERFUMERY => 'SM Perfumery(Toko/Multicabang)',
            self::CUSTOMER_TYPE_UMUM_PROJECT => 'Umum Project(Semua Prospek)',
            self::CUSTOMER_TYPE_HOME_INDUSTRI_KOSMETIK => 'Home Industri Kosmetik',
            self::CUSTOMER_TYPE_HOME_INDUSTRI_PKRT => 'Home Industri PKRT',
            self::CUSTOMER_TYPE_INDUSTRI_KOSEMTIK => 'Industri Kosmetik(PPN)',
            self::CUSTOMER_TYPE_INDUSTRI_PKRT => 'Industri PKRT(PPN)'
        ];

        return empty($type) ? $types : (isset($types[$type]) ? $types[$type] : "");
    }

    public function getCustomerStatus($status='')
    {
        $statuses = [
            self::CUSTOMER_STATUS_INACTIVE => 'Tidak Active',
            self::CUSTOMER_STATUS_ACTIVE => 'Active',
        ];

        return empty($status) ? $statuses : (isset($statuses[$status]) ? $statuses[$status] : "");
    }

    public function getCustomerName(){
        return $this->customer_store_name.' - '.$this->customer_owner_name;
    }

    public function getCustomerPhone(){
        $telp = '-';    

        if(isset($this->customer_phone1) && isset($this->customer_phone2)){
           $telp =  $this->customer_phone1.'<br>'.$this->customer_phone2;
        }
        elseif(!isset($this->customer_phone1) && isset($this->customer_phone2)){
            $telp =  $this->customer_phone2;
        }
        elseif(isset($this->customer_phone1) && !isset($this->customer_phone2)){
            $telp =  $this->customer_phone1;
        }

        return $telp;                                   
    }

    public function getCustomerDebtAmount(){
        $invoice = SalesInvoice::find()->where('customer_id=:id', [':id' => $this->id])
        ->andWhere('is_deleted=:is', [':is' => 0])->andWhere('invoice_payment_status != :p', [':p' => 'paid'])->all();

        $outstanding = 0;
        if(!empty($invoice)){
            foreach ($invoice as $item) {
                $out = 0;
                if($item->invoice_exchange_rate > 1)
                    $out = $item->invoice_outstanding_amount / $item->invoice_exchange_rate;
                else
                    $out = $item->invoice_outstanding_amount;

                $outstanding += $out;
            }
        }
        return $outstanding;
    }
}
