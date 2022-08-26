<?php
use yii\helpers\Url;
use common\components\CurrencyComponent;
use common\models\Product;
use common\models\SalesPayment;
use common\models\SalesInvoiceItem;
use common\models\SalesInvoice;
use common\models\Bank;

function penyebut($nilai) {
        $nilai = abs($nilai);
        $huruf = array("", "satu", "dua", "tiga", "empat", "lima", "enam", "tujuh", "delapan", "sembilan", "sepuluh", "sebelas");
        $temp = "";
        if ($nilai < 12) {
            $temp = " ". $huruf[$nilai];
        } else if ($nilai <20) {
            $temp = penyebut($nilai - 10). " belas";
        } else if ($nilai < 100) {
            $temp = penyebut($nilai/10)." puluh". penyebut($nilai % 10);
        } else if ($nilai < 200) {
            $temp = " seratus" . penyebut($nilai - 100);
        } else if ($nilai < 1000) {
            $temp = penyebut($nilai/100) . " ratus" . penyebut($nilai % 100);
        } else if ($nilai < 2000) {
            $temp = " seribu" . penyebut($nilai - 1000);
        } else if ($nilai < 1000000) {
            $temp = penyebut($nilai/1000) . " ribu" . penyebut($nilai % 1000);
        } else if ($nilai < 1000000000) {
            $temp = penyebut($nilai/1000000) . " juta" . penyebut($nilai % 1000000);
        } else if ($nilai < 1000000000000) {
            $temp = penyebut($nilai/1000000000) . " milyar" . penyebut(fmod($nilai,1000000000));
        } else if ($nilai < 1000000000000000) {
            $temp = penyebut($nilai/1000000000000) . " trilyun" . penyebut(fmod($nilai,1000000000000));
        }     
        return $temp;
    }
 
    function terbilang($nilai) {
        if($nilai<0) {
            $hasil = "minus ". trim(penyebut($nilai));
        } else {
            $hasil = trim(penyebut($nilai));
        }           
        return $hasil;
    }
?>
<table border="0" style="width: 100%;">
    <tr>
        <td class="" style="width: 45%;">
        </td>
        <td>
            <div class="store-name"><u>INVOICE</u></div>
        </td>
    </tr>
</table>
<table  border="0" style="width: 100%; font-size: 12px;">
  <tr>
    <td style="width: 65%;">
      <table style="width: 100%;" border="0">

      <!-- <tr>
        
        <td style="width: 20%;">No Invoice</td>
        <td style="width: 5%;">:</td>
        <td style="width: 75%;"><?= $model->invoice_code?></td>
      </tr>
      <tr>
        
        <td>Tanggal</td>
        <td>:</td>
        <td><?= date("d-m-Y", strtotime($model->invoice_date))?></td>
      </tr> -->
      <tr>
        <?php 
            $storeName = isset($model->customer->customer_store_name) ? $model->customer->customer_store_name : ''; 
            $storeName1 = substr($storeName, 0, 50);
            $storeName2 = substr($storeName, 50, 50);
            $storeName3 = substr($storeName, 100, 50);
         ?>
        
        <td style="width: 20%;">Pelanggan</td>
        <td style="width: 5%;">:</td>
        <td style="width: 75%;"><?= $storeName1 ?></td>
      </tr>
      <?php if(!empty($storeName2)): ?>
        <tr>
        
        <td></td>
        <td>:</td>
        <td><?= $storeName2 ?></td>
      </tr>
      <?php endif; ?>
      <?php if(!empty($storeName3)): ?>
        <tr>
        
        <td></td>
        <td>:</td>
        <td><?= $storeName3 ?></td>
      </tr>
      <?php endif; ?>
      <tr>
        <?php 
            $up = isset($model->customer->customer_owner_name) ? $model->customer->customer_owner_name : ''; 
            $up1 = substr($up, 0, 50);
            $up2 = substr($up, 50, 50);
            $up3 = substr($up, 100, 50);
         ?>
        
        <td>UP.</td>
        <td>:</td>
        <td><?= $up1 ?></td>
      </tr>
      <?php if(!empty($up2)): ?>
        <tr>
        
        <td></td>
        <td>:</td>
        <td><?= $up2 ?></td>
      </tr>
      <?php endif; ?>
      <?php if(!empty($up3)): ?>
        <tr>
        
        <td></td>
        <td>:</td>
        <td><?= $up3 ?></td>
      </tr>
      <?php endif; ?>
      <tr>
        <?php
            $city = isset($model->invoice_destination_city) ? ", ".$model->invoice_destination_city : ''; 
            $province = isset($model->invoice_destination_province) ? ", ".$model->invoice_destination_province : '';
            $temp = isset($model->invoice_destination_address) ? $model->invoice_destination_address.$city.$province : '';
            $address1 = substr($temp, 0, 60);
            $address2 = substr($temp, 60, 60);
            $address3 = substr($temp, 120, 60);
         ?>
        
        <td>Alamat Kirim</td>
        <td>:</td>
        <td><?= $address1?></td>
      </tr>
      <tr>
        
        <td>&nbsp;</td>
        <td>:</td>
        <td><?= $address2?></td>
      </tr>
      <!-- <tr>
        
        <td>&nbsp;</td>
        <td>:</td>
        <td><?= $address3?></td>
      </tr> -->
      <tr>
        
        <td>Telp.</td>
        <td>:</td>
        <td><?= isset($model->customer->customer_phone1) ? $model->customer->customer_phone1 : ''?></td>
      </tr>
    </table>
    </td>
    <td style="width: 35%;">
    <table style="width: 100%;" border="0">

      <!-- <tr>
        <td style="width: 30%;">&nbsp;</td>
        <td style="width: 5%;">&nbsp;</td>
        <td style="width: 65%;">&nbsp;</td>
      </tr> -->
      <!-- <tr>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
      </tr> -->
      <!-- <tr>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
      </tr> -->
      <!-- <tr>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
      </tr> -->
      <!-- <tr>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
      </tr>
      <tr>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
      </tr> -->
      <tr>
        <td style="width: 30%;">No Invoice</td>
        <td style="width: 5%;" >:</td>
        <td style="width: 65%;"><?= $model->invoice_code?></td>
      </tr>
      <tr>
        <td>Tanggal</td>
        <td>:</td>
        <td><?= date("d-m-Y", strtotime($model->invoice_date))?></td>
      </tr>
      <tr>
      <tr>
        <td>NIK/NPWP</td>
        <td>:</td>
        <td><?= isset($model->customer->customer_npwp) ? $model->customer->customer_npwp : ''?></td>
      </tr>
      <tr>
        <?php 
            if(isset($model->customer->customer_tempo_limit)){

            }
         ?>
        <td><strong>Jatuh Tempo</strong></td>
        <td>:</td>
        <td><strong><?= isset($model->customer->customer_tempo_limit) ? date('d-m-Y', strtotime($model->invoice_date. ' + '.$model->customer->customer_tempo_limit.' days')) : ''?></strong></td>
      </tr>
      <tr>
        <td>Sales</td>
        <td>:</td>
        <td><?= isset($model->salesman->employee_name) ? $model->salesman->employee_name : ''?></td>
      </tr>
    </table>
    </td>
  </tr>
</table>
<!-- <hr style="margin: 1px 0px 5px 0px;"> -->
<?php if(!$model->isPayment()): ?>
<table cellspacing="0" class="table-product" style="font-size: 11px; border: 1px solid;">
  <tr>
    <th class="no" style="width: 5%;"><center>No</center></th>
    <th class="product-code" style="width: 10%;"><center>Kode Barang</center></th>
    <th class="product-name" style="width: 15%;"><center>Nama Barang</center></th>
    <th class="acuan" style="width: 10%;"><center>Acuan (USD)</center></th>
    <th class="qty" style="width: 5%;"><center>Qty (KG)</center></th>
    <th class="packaging" style="width: 10%;"><center>Kemasan</center></th>
    <th  class="price" style="width: 10%;"><center>Harga</center></th>
    <th class="discon" style="width: 10%;"><center>Diskon (Cash)</center></th>
    <th class="netto" style="width: 10%;"><center>Netto</center></th>
    <th style="width: 15%;"><center>Jumlah</center></th>
  </tr>
  <?php
        $currency = Product::CURRENCY_DOLAR;
        if($model->invoice_exchange_rate > 1)
            $currency = Product::CURRENCY_RUPIAH;
        $cur = '';

        $i = 1;
        // $items = $model->salesInvoiceItems;
        $items = SalesInvoiceItem::find()->joinWith(['product'])->andWhere('invoice_id = :id', [':id' => $model->id])->orderBy(['product_name' => SORT_ASC])->all();
        $total = 0;
        foreach ($items as $item) {

            $usd = $item->product->product_sell_price;
            $price = $item->product->product_sell_price;
            $discamountitem = $item->invoice_item_disc_amount;

            if($model->invoice_exchange_rate > 1){
                $price = $item->product->product_sell_price * $model->invoice_exchange_rate;
                $discamountitem = $item->invoice_item_disc_amount * $model->invoice_exchange_rate;
            }

            $netto = $price - $discamountitem;
            $subtotal = $netto * $item->invoice_item_qty; 
            $total += $subtotal;
    ?>
            <tr>
                <td class="no"><center><?= $i?></center></td>
                <td class="product-code"><?= $item->product->product_code?></td>
                <td class="product-name"><?= $item->product->product_name?></td>
                <td class="acuan"><center><?= CurrencyComponent::formatMoney2($usd,0,',','.', $cur)?></center></td>
                <td class="qty"><center><?= $item->invoice_item_qty?></center></td>
                <td class="packaging"><center><?= $item->packaging->packaging_name?></center></td>
                <td class="price" style="text-align: right;"><?= CurrencyComponent::formatMoney2($price,0,',','.', $cur)?></td>
                <td class="discon" style="text-align: right;"><?= CurrencyComponent::formatMoney2($discamountitem,0,',','.', $cur)?></td>
                <td class="netto" style="text-align: right;"><?= CurrencyComponent::formatMoney2($netto,0,',','.', $cur)?></td>
                <td style="text-align: right;"><?= CurrencyComponent::formatMoney2($subtotal,0,',','.', $cur)?></td>
            </tr>
    <?php
        $i++;
        }
        $totalafterdiscon = $total - ($model->invoice_disc_amount + $model->invoice_disc_amount2);
        $totalaftertax = $totalafterdiscon + $model->invoice_tax_amount;
        $tips = $model->invoice_shipping_cost;
        $grandTotal = $totalaftertax + $tips
     ?>
</table>
<!-- <br> -->
<table style="width: 100%; font-size: 14px; margin-top: -12px;" border="0">
  <tr>
    <td style="width: 70%;" scope="col"><div align="left">
      <table style="width: 85%; font-size: 14px;" border="0">
        <tr>
          <td width="147" scope="col"><div align="left">Terbilang</div></td>
          <td width="107" scope="col">&nbsp;</td>
          <td width="368" scope="col">&nbsp;</td>
        </tr>
        <tr>
          <td colspan="3"><?= terbilang($grandTotal).' '.$currency?></td>
          </tr>
          <!-- <tr style="height: 1px;">
          <td colspan="3" style="height: 1px;">&nbsp;</td>
          </tr> -->
          <tr>
          <td>*Kurs USD</td>
          <td>:</td>
          <td><?= $model->invoice_exchange_rate?></td>
          </tr>
        </table>
        <table style="width: 100%; border-style: solid; border-size:1px;" cellspacing="0" class="note">
        <tr>
            <td style="width: 5%;"> - </td>
            <td><strong>Pembayaran Cheque / Wesel / BG dianggap sah bila telah diuangkan</strong></td>
          </tr>
        <tr>
            <td style="width: 5%;"> - </td>
          <td><strong>Pembayaran TUNAI wajib disertai TANDA TERIMA TUNAI resmi dari PPI</strong></td>
          </tr>
        
        <tr>
            <td style="width: 5%;"> - </td>
          <td><strong>Pembayaran diluar ketentuan diatas tidak diakui</strong></td>
          </tr>
        <tr>
            <td style="width: 5%;"> - </td>
          <td><strong>Barang yang sudah dibeli tidak dapat ditukar / dikembalikan</strong></td>
        </tr>
      </table>
    </div></td>
    <!-- <br> -->
    <td style="width: 30%;" scope="col"><div align="left">
    <table style="width: 100%; margin-top: 10spx; font-size: 14px;" border="0">
      <tr>
        <td width="187" scope="col"><div align="left">Sub Total</div></td>
        <td width="11" scope="col">:</td>
        <td width="101" scope="col" style="text-align: right;"><div align="right"><?= CurrencyComponent::formatMoney($total,0,',','.', $currency)?></div></td>
      </tr>
      <tr>
        <td><div align="left">Diskon <?= $model->invoice_disc_percent?>%</div></td>
        <td>:</td>
        <td style="text-align: right;"><div align="right"><?= $model->invoice_disc_amount >= 0.1 ? CurrencyComponent::formatMoney($model->invoice_disc_amount,0,',','.', $currency) : '-'?></div></td>
      </tr>
      <tr>
        <td><div align="left">Voucher</div></td>
        <td>:</td>
        <td style="border-bottom: solid 1px; text-align: right;"><div align="right"><?= $model->invoice_disc_amount2 >= 0.1 ? CurrencyComponent::formatMoney($model->invoice_disc_amount2,0,',','.', $currency) : '-'?></div></td>
      </tr>
      <tr>
        
        <td><div align="left"></div></td>
        <td>:</td>
        <td style="text-align: right;"><div align="right"><?= CurrencyComponent::formatMoney($totalafterdiscon,0,',','.', $currency) ?></div></td>
      </tr>
      <tr>
        
        <td><div align="left">PPN <?= $model->invoice_tax_percent?>%</div></td>
        <td>:</td>
        <td style="border-bottom: solid 1px; text-align: right;"><div align="right"><?= $model->invoice_tax_amount >= 0.1 ? CurrencyComponent::formatMoney($model->invoice_tax_amount,0,',','.', $currency) : '-'?></div></td>
      </tr>
      <tr>
        
        <td><div align="left">Total</div></td>
        <td>:</td>
        <td style="text-align: right;"><div align="right"><?= CurrencyComponent::formatMoney($totalaftertax,0,',','.', $currency)?></div></td>
      </tr>
      <tr>
        
        <td><div align="left">Biaya Kirim</div></td>
        <td>:</td>
        <td align="right" style="text-align: right;"><?= $tips >= 0.1 ? CurrencyComponent::formatMoney($tips,0,',','.', $currency) : '-'?></td>
      </tr>
      <tr>
        
        <td><div align="left"><strong>Grand Total</strong></div></td>
        <td>:</td>
        <td style="text-align: right;"><div align="right"><strong><?= CurrencyComponent::formatMoney($grandTotal,0,',','.', $currency)?></strong></div></td>
      </tr>
    </table></td>
  </tr>
</table>
<?php else: ?>
<?php 
  //========================================================================================================================================================================================================================================================================
 ?>
<table cellspacing="0" class="table-product" style="font-size: 12px; border: 1px solid;">
  <tr>
    <th class="no" style="width: 5%;"><center>No</center></th>
    <th class="product-code" style="width: 10%;"><center>Kode Barang</center></th>
    <th class="product-name" style="width: 15%;"><center>Nama Barang</center></th>
    <th class="acuan" style="width: 10%;"><center>Acuan (USD)</center></th>
    <th class="qty" style="width: 5%;"><center>Qty (KG)</center></th>
    <th class="packaging" style="width: 10%;"><center>Kemasan</center></th>
    <th class="price" style="width: 10%;"><center>Harga</center></th>
    <th class="discon" style="width: 10%;"><center>Diskon (Cash)</center></th>
    <th class="netto" style="width: 10%;"><center>Netto</center></th>
    <th style="width: 15%;"><center>Jumlah</center></th>
  </tr>
  <?php
        $payment = SalesPayment::find()->where('invoice_id = :id AND is_deleted = :is', [':id' => $model->id, ':is' => 0])->one();
        $currency = Product::CURRENCY_RUPIAH;
        $cur = '';

        $i = 1;
        // $items = $model->salesInvoiceItems;
        $items = SalesInvoiceItem::find()->joinWith(['product'])->andWhere('invoice_id = :id', [':id' => $model->id])->orderBy(['product_name' => SORT_ASC])->all();
        $total = 0;
        foreach ($items as $item) {

            $usd = $item->product->product_sell_price;
            
            $price = $item->product->product_sell_price * $payment->payment_exchange_rate;
            $discamountitem = $item->invoice_item_disc_amount * $payment->payment_exchange_rate;
            
            $netto = $price - $discamountitem;
            $subtotal = $netto * $item->invoice_item_qty; 
            $total += $subtotal;
    ?>
            <tr>
                <td class="no"><center><?= $i?></center></td>
                <td class="product-code"><?= $item->product->product_code?></td>
                <td class="product-name"><?= $item->product->product_name?></td>
                <td class="acuan"><center><?= CurrencyComponent::formatMoney2($usd,0,',','.', $cur)?></center></td>
                <td class="qty"><center><?= $item->invoice_item_qty?></center></td>
                <td class="packaging"><center><?= $item->packaging->packaging_name?></center></td>
                <td class="price" style="text-align: right;"><?= CurrencyComponent::formatMoney2($price,0,',','.', $cur)?></td>
                <td class="discon" style="text-align: right;"><?= CurrencyComponent::formatMoney2($discamountitem,0,',','.', $cur)?></td>
                <td class="netto" style="text-align: right;"><?= CurrencyComponent::formatMoney2($netto,0,',','.', $cur)?></td>
                <td style="text-align: right;"><?= CurrencyComponent::formatMoney2($subtotal,0,',','.', $cur)?></td>
            </tr>
    <?php
        $i++;
        }

        $shippingCost = isset($model->invoice_shipping_cost) ? $model->invoice_shipping_cost : 0;
        if($model->invoice_exchange_rate > 1){
            $discamount = $model->invoice_disc_amount;
            $taxamount = $model->invoice_tax_amount;
            $discamount2 = $model->invoice_disc_amount2;
        }
        else{
            $discamount = $model->invoice_disc_amount * $payment->payment_exchange_rate;
            $taxamount = $model->invoice_tax_amount * $payment->payment_exchange_rate;
            $discamount2 = $model->invoice_disc_amount2 * $payment->payment_exchange_rate;
            $shippingCost = $shippingCost * $payment->payment_exchange_rate;
        }
        $totalafterdiscon = $total - ($discamount + $discamount2);
        $totalaftertax = $totalafterdiscon + $taxamount;
        $tips = $shippingCost;
        $grandTotal = $totalaftertax + $tips

     ?>
</table>
<!-- <br> -->
<table style="width: 100%; font-size: 14px;">
  <tr>
    <td style="width: 75%;" scope="col"><div align="left">
      <table style="width: 100%; font-size: 14px;">
        <tr>
          <td width="147" scope="col"><div align="left">Terbilang</div></td>
          <td width="107" scope="col">&nbsp;</td>
          <td width="368" scope="col">&nbsp;</td>
          </tr>
        <tr>
          <td colspan="3"><?= terbilang($grandTotal).' '.$currency?></td>
          </tr>
          <!-- <tr>
          <td colspan="3">&nbsp;</td>
          </tr> -->
          <tr>
          <td>*Kurs USD</td>
          <td>:</td>
          <td><?= $payment->payment_exchange_rate?></td>
          </tr>
        </table>
        <table style="width: 100%; border-style: solid; border-size:1px;" cellspacing="0" class="note">
            <td style="width: 5%;"> - </td>
          <td><strong>Pembayaran Cheque / Wesel / BG dianggap sah bila telah diuangkan</strong></td>
          </tr>
        <tr>
            <td style="width: 5%;"> - </td>
          <td><strong>Pembayaran TUNAI wajib disertai TANDA TERIMA TUNAI resmi dari Kantor</strong></td>
          </tr>
        
        <tr>
            <td style="width: 5%;"> - </td>
            <td><strong>Pembayaran diluar ketentuan diatas tidak diakui</strong></td>
        </tr>
        <tr>
            <td style="width: 5%;"> - </td>
          <td><strong>Barang yang sudah dibeli tidak dapat ditukar / dikembalikan</strong></td>
        </tr>
      </table>
    </div></td>
    <td style="width: 30%;" scope="col">
    <table style="width: 100%; margin-top: -20px; font-size: 14px;">
      <tr>
        <td width="187" scope="col"><div align="left">Sub Total</div></td>
        <td width="11" scope="col">:</td>
        <td width="101" scope="col" style="text-align: right;"><div align="right"><?= CurrencyComponent::formatMoney($total,0,',','.', $currency)?></div></td>
      </tr>
      <tr>
        
        <td><div align="left">Diskon <?= $model->invoice_disc_percent?>%</div></td>
        <td>:</td>
        <td style="text-align: right;"><div align="right"><?= $discamount >= 0.1 ? CurrencyComponent::formatMoney($discamount,0,',','.', $currency) : '-'?></div></td>
      </tr>
      <tr>
        <td><div align="left">Voucher</div></td>
        <td>:</td>
        <td style="border-bottom: solid 1px; text-align: right;"><div align="right"><?= $discamount2 >= 0.1 ? CurrencyComponent::formatMoney($discamount2,0,',','.', $currency) : '-'?></div></td>
      </tr>
      <tr>
        
        <td><div align="left"></div></td>
        <td>:</td>
        <td style="text-align: right;"><div align="right"><?= CurrencyComponent::formatMoney($totalafterdiscon,0,',','.', $currency) ?></div></td>
      </tr>
      <tr>
        
        <td><div align="left">PPN <?= $model->invoice_tax_percent?>%</div></td>
        <td>:</td>
        <td style="border-bottom: solid 1px; text-align: right;"><div align="right"><?= $taxamount >= 0.1 ? CurrencyComponent::formatMoney($taxamount,0,',','.', $currency) : '-'?></div></td>
      </tr>
      <tr>
        
        <td><div align="left">Total</div></td>
        <td>:</td>
        <td style="text-align: right;"><div align="right"><?= CurrencyComponent::formatMoney($totalaftertax,0,',','.', $currency)?></div></td>
      </tr>
      <tr>
        
        <td><div align="left">Biaya Kirim</div></td>
        <td>:</td>
        <td align="right" style="text-align: right;"><?= $tips >= 0.1 ? CurrencyComponent::formatMoney($tips,0,',','.', $currency) : '-'?></td>
      </tr>
      <tr>
        
        <td><div align="left"><strong>Grand Total</strong></div></td>
        <td>:</td>
        <td style="text-align: right;"><div align="right"><strong><?= CurrencyComponent::formatMoney($grandTotal,0,',','.', $currency)?></strong></div></td>
      </tr>
    </table></td>
  </tr>
</table>
<?php endif; ?>
<table style="width: 100%; font-size: 14px;">
  <tr>
    <td style="width: 80%;" scope="col" align="center">
      <div align="center">
          <table style="width: 100%; font-size: 14px;">
              <tr>
                <td><?= null ?></div></td>
              </tr>
          </table>
      </div>
    </td>
    <td style="width: 25%;" scope="col"><div align="left">
    <table style="width: 100%; margin-top: -5px; font-size: 14px;">
      <tr>
        <td width="187" scope="col"><div align="left">
            Hormat Kami,<br/><br/><br/><br/>
            
            (&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;)
        </div></td>
      </tr>
    </table></td>
  </tr>
</table>
