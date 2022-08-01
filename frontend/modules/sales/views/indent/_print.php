<?php
use yii\helpers\Url;
use common\components\CurrencyComponent;
?>
<table border="0" style="width: 100%;">
	<tr>
		<td class="col-left">
			<div class="store-name">TRIVIA INDOBAG MANDIRI</div>
			<br/>
			<div class="store-address">
				Jl. Tempel Sukorejo 1 no. 47<br/>
				Surabaya 60235<br/>
				Jawa Timur - Indonesia
			</div>
			<div class="store-phone">Telp. 031 546 5333 , 081 80300 5333</div>
			<div class="store-email">Email: irwantrivia@yahoo.com</div>
		</td>
		<td class="col-right">
			<div class="store-logo"><img style="width: 80px;" src="<?=Url::base(true)?>/img/logo.png" alt="" /></div><br/>
			<div class="date">Tanggal: <?=date("d-m-Y", strtotime($model->order_date))?></div>
		</td>
	</tr>
</table>
<br/>
<div class="pdf-title">FAKTUR</div>
<div class="customer-title">PELANGGAN</div>
<table border="0">
	<tr>
		<td style="width: 1px;">Merek</td>
		<td> : <?=$model->product->product_name?></td>
	</tr>
	<tr>
		<td>Nama</td>
		<td> : <?=$model->order_customer_name?></td>
	</tr>
	<tr>
		<td>Alamat</td>
		<td> : <?=$model->order_customer_address?></td>
	</tr>
	<tr>
		<td>Telp</td>
		<td> : <?=$model->order_customer_phone?></td>
	</tr>
</table>
<br/>
<div style="border-bottom: 1px solid #000; margin-bottom: 10px;"><b>DATA PRODUK</b><br/></div>
<table border="0" cellspacing="0" class="table-product">
	<tr>
		<td style="width: 20%">Ukuran/Bentuk</td>
		<td style="width: 2%"> : </td>
		<td style="width: 15%" colspan="2">P <?=$model->product->product_length;?></td>
		<td style="width: 15%" colspan="2">T <?=$model->product->product_height?></td>
		<td style="width: 15%" colspan="2">L <?=$model->product->product_width?></td>
		<td style="width: 33%"><?=$model->product->getBoxTypeLabel($model->product->product_box_type)?></td>
	</tr>
	<tr>
		<td>Warna Bahan</td>
		<td> : </td>
		<td colspan="7"><?=$model->product->product_material_color?></td>
	</tr>
	<tr>
		<td>Warna Sablon</td>
		<td> : </td>
		<td colspan="6"><?=$model->product->product_screen_color?></td>
		<td>
			<div style="white-space: nowrap">
		<?php foreach($model->product->getSideLabel() as $value => $label):?>
			<?php if ($value != $model->product->product_num_of_side):?>
				<del><?=$label?></del>
			<?php else:?>
				<?=$label?>
			<?php endif;?>
			&nbsp;&nbsp;
		<?php endforeach;?>
			</div>
		</td>
	</tr>
	<tr>
		<td>Perekat</td>
		<td> : </td>
		<td colspan="7">
			<div style="white-space: nowrap">
		<?php foreach($model->product->getVariationLabel() as $value => $label):?>
			<?php if ($value != $model->product->product_variation):?>
				<del><?=$label?></del>
			<?php else:?>
				<?=$label?>
			<?php endif;?>
			&nbsp;&nbsp;
		<?php endforeach;?>
			</div>
		</td>
	</tr>
</table>
<table border="0" cellspacing="0">
	<tr>
		<td rowspan="2" style="width: 20%; vertical-align: top">Ilustrasi Produk</td>
		<td rowspan="2"  style="width: 2%; vertical-align: top"> : </td>
		<td style="vertical-align: top; text-align: right;"><br/><br/><br/><br/><br/><?=$model->product->product_height?></td>
		<td style="vertical-align: top; width: 1px;"><br/><img src="<?=Url::base(true)?>/img/ilustration.png" alt="" /></td>
		<td style="vertical-align: top"><br/><br/><br/><br/><br/><br/><?=$model->product->product_length?></td>
		<td rowspan="2" style="vertical-align: top; width: 30%">Catatan:<br/><?=$model->product->product_note?></td>
	</tr>
	<tr>
		<td colspan="3" style="text-align: center;"><?=$model->product->product_width?></td>
	</tr>
</table>
Jumlah Order: <?=$model->order_qty?> pcs<br/>
Harga Satuan: <?=CurrencyComponent::formatMoney($model->order_price)?><br/>
<strong>TOTAL: <?=CurrencyComponent::formatMoney($model->order_total)?></strong>
<br/>
<br/>
<table border="0">
	<tr>
		<td style="width: 40%; vertical-align: top">
			<table border="1" cellspacing="0" style="width: 100%;">
				<tr>
					<td>
						<center><strong>Note: mohon DP ditransfer sesuai nominal yang tertera</strong></center><br/>
						BCA cab HR Muhammad<br/>
						A/C 829 023 6577<br/>
						A/N Irwan Santoso
					</td>
				</tr>
			</table>
		</td>
		<td style="width: 20%">&nbsp;</td>
		<td style="width: 40%; vertical-align: top; text-align: center;">
			Hormat Kami,<br/><br/>
			<img src="<?=Url::base(true)?>/img/signature.png" alt="" /><br/><br/>
			(TRIVIA INDOBAG MANDIRI)
		</td>
	</tr>
</table>