<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
// use yii\widgets\ActiveForm;
use yii\bootstrap\ActiveForm;
use common\components\CurrencyComponent;
use frontend\components\LabelComponent;
use frontend\components\AccessComponent;
use app\components\BaseController;
use frontend\components\ButtonComponent;
use common\models\Bank;
use common\models\Product;
use common\models\Customer;
use common\models\SalesInvoice;
use common\models\SalesInvoiceItem;
use common\models\SalesPaymentDetail;

$this->title = BaseController::getCustomPageTitle(BaseController::$page_caption);
$toolbar[] = ButtonComponent::getBackButton();
$toolbar[] = ButtonComponent::getSaveButton();
	
BaseController::$toolbar = $toolbar;
	
foreach(Yii::$app->session->getAllFlashes() as $key => $message)
	echo '<div class="alert alert-' . $key . '">' . $message . "</div>\n";

?>
<input id="baseUrl" type="hidden" value="<?=Url::base()?>/sales/salespayment">
<?php $form = ActiveForm::begin(['id' => 'application_form']); ?>
<?php echo Html::hiddenInput('saveandnew', '0', ['id' => 'hiddensaveandnew']);?>
<?php //echo $form->errorSummary(array($model));?>

<section class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-content">
                <div class="card-body padding-bottom-zero padding-top-zero"> 
                    <div class="row">
                        <div class="col-lg-4">
                            <?= $form->field($model, 'payment_date')->textInput(['class'=>'form-control input-sm date-inputmask', "data-inputmask" =>"'alias': 'datetime','inputFormat': 'dd-mm-yyyy'"]) ?>
                        </div>
                        <div class="col-lg-4">
                            <?= $form->field($model, 'payment_exchange_rate')->textInput(['class'=>'form-control input-sm angka']) ?>
                        </div>
                        <div class="col-lg-4">
                           <?= $form->field($model, 'payment_notes')->textInput(['class'=>'form-control input-sm']) ?>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-4">
                            <?= $form->field($model, 'customer_id')
                            ->dropDownList(ArrayHelper::map(customer::find()->where('is_deleted=:is', [':is' => 0])->all(),'id','customerName' ), ['class' => 'form-control input-sm select2', 'prompt' => 'Pilih Customer']);
                            ?>
                        </div>
                        <div class="col-lg-4">
                            <?= $form->field($model, 'invoice_id')
                                ->dropDownList(isset($model->invoice_id) ? ArrayHelper::map(SalesInvoice::find()->where('customer_id=:c', [':c' => $model->customer_id])->andWhere('id =:id', [':id' => $model->invoice_id])
                                                                 ->andWhere('is_deleted=:is', [':is' => 0])->orderBy(['invoice_code' => SORT_ASC])->all(), 'id', 'invoice_code') : array('')
                                ,['class' => 'form-control input-sm select2', 'prompt' => 'Pilih Nota']);
                            ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<section class="row payment-session">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">Detail Nota</h4>
                <a class="heading-elements-toggle"><i class="la la-ellipsis-v font-medium-3"></i></a>
                <div class="heading-elements">
                    <ul class="list-inline mb-0">
                        <li><a data-action="collapse"><i class="ft-minus"></i></a></li>
                    </ul>
                </div>
            </div>
            <div class="card-content">
                <div class="card-body padding-top-zero padding-bottom-zero">
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="row mb-1">
                                <div class="col-lg-4"><b><?=(new SalesInvoice)->getAttributeLabel('invoice_code')?></b></div>
                                <div class="col-lg-1">:</div>
                                <div class="col-lg-5"><span class="detail-invoice-code"><?=isset($model->invoice->invoice_code) ? $model->invoice->invoice_code : '' ?></span></div>
                            </div>  
                            <div class="row mb-1">
                                <div class="col-lg-4"><b><?=(new SalesInvoice)->getAttributeLabel('invoice_date')?></b></div>
                                <div class="col-lg-1">:</div>
                                <div class="col-lg-5"><span class="detail-invoice-date"><?=isset($model->invoice->invoice_date) ? $model->invoice->invoice_date : '' ?></span></div>
                            </div>  
                            <div class="row mb-1">
                                <div class="col-lg-4"><b><?=(new SalesInvoice)->getAttributeLabel('salesman_id')?></b></div>
                                <div class="col-lg-1">:</div>
                                <div class="col-lg-5"><span class="detail-invoice-salesman"><?= isset($model->invoice->salesman->employee_name) ? $model->invoice->salesman->employee_name : ''?></span></div>
                            </div>
                            <div class="row mb-1">
                                <div class="col-lg-4"><b><?=(new SalesInvoice)->getAttributeLabel('customer_id')?></b></div>
                                <div class="col-lg-1">:</div>
                                <div class="col-lg-5"><span class="detail-invoice-customer"><?=isset($model->invoice->customer->customerName) ? $model->invoice->customer->customerName : '' ?></span></div>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="row mb-1">
                                <div class="col-lg-4"><b><?=(new SalesInvoice)->getAttributeLabel('comission_type_id')?></b></div>
                                <div class="col-lg-1">:</div>
                                <div class="col-lg-5"><span class="detail-invoice-comission"><?=isset($model->invoice->comissionType->comission_type_name) ? $model->invoice->comissionType->comission_type_name : ''?></span></div>
                            </div>
                            <div class="row mb-1">
                                <div class="col-lg-4"><b><?=(new SalesInvoice)->getAttributeLabel('invoice_comission_value')?></b></div>
                                <div class="col-lg-1">:</div>
                                <div class="col-lg-5"><span class="detail-invoice-comissionvalue"><?=isset($model->invoice->invoice_comission_value) ? $model->invoice->invoice_comission_value : ''?></span></div>
                            </div>
                            <div class="row mb-1">
                                <div class="col-lg-4"><b><?=(new SalesInvoice)->getAttributeLabel('invoice_exchange_rate')?></b></div>
                                <div class="col-lg-1">:</div>
                                <div class="col-lg-5">
                                    <span class="detail-invoice-exchangerate-label"><?=isset($model->invoice->invoice_exchange_rate) ? $model->invoice->invoice_exchange_rate : ''?></span>
                                    <input type="hidden" class="detail-invoice-exchangerate" value="<?=isset($model->invoice->invoice_exchange_rate) ? $model->invoice->invoice_exchange_rate : ''?>">
                                </div>
                            </div>
                        </div>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-lg-12">       
                            <div class="table-responsive">
                                <table class="table table-striped table-bordered table-item">
                                    <thead>
                                        <tr>
                                            <th style="width: 25%"><center><?= (new SalesInvoiceItem)->getAttributeLabel('product_id')?></center></th>
                                            <th style="width: 10%"><center><?= (new SalesInvoiceItem)->getAttributeLabel('invoice_item_qty')?></center></th>
                                            <th style="width: 15%;"><center><?= (new SalesInvoiceItem)->getAttributeLabel('invoice_item_disc_amount')?></center></th>
                                            <th style="width: 25%"><center><?= (new SalesInvoiceItem)->getAttributeLabel('invoice_item_price')?></center></th>
                                            <th style="width: 25%;"><center><?= (new SalesInvoiceItem)->getAttributeLabel('invoice_item_row_total')?></center></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if($mode == 'update' && isset($model->invoice->salesInvoiceItems)): ?>
                                        <?php foreach($model->invoice->salesInvoiceItems as $item):?>
                                        <tr class="row-item">
                                            <td>
                                                <center><span class="product-name"><?= $item->product->product_code.' -- '.$item->product->product_name ?></span></center>
                                            </td>
                                            <td class="text-right">
                                                <span><?= $item->invoice_item_qty ?></span>
                                            </td>
                                            <td class="text-right">
                                                <?= CurrencyComponent::formatMoney($item->invoice_item_disc_amount,0,',','.', Product::CURRENCY_DOLAR) ?>
                                            </td>
                                            <td class="text-right">
                                                <?= CurrencyComponent::formatMoney($item->invoice_item_price,0,',','.', Product::CURRENCY_DOLAR) ?>
                                            </td>
                                            <td class="text-right">
                                                <?php if($model->invoice->invoice_exchange_rate > 1): ?>
                                                    <?= CurrencyComponent::formatMoney($item->invoice_item_row_total) ?>
                                                <?php else: ?>
                                                    <?= CurrencyComponent::formatMoney($item->invoice_item_row_total,0,',','.', Product::CURRENCY_DOLAR) ?>
                                                <?php endif; ?>
                                            </td>
                                        </tr>
                                        <?php endforeach;?>
                                        <?php endif; ?>
                                    <tr>
                                        <td colspan="4" class="text-right"><span><b><?= (new SalesInvoice)->getAttributeLabel('invoice_subtotal')?></b></span></td>
                                        <td class="text-right">
                                            <strong><span class="detail-invoice-subtotal">
                                                <?php if(isset($model->invoice->salesInvoiceItems)): ?>
                                                    <?php if($model->invoice->invoice_exchange_rate > 1): ?>
                                                        <?= CurrencyComponent::formatMoney($model->invoice->invoice_subtotal)?>
                                                    <?php else: ?>
                                                        <?= CurrencyComponent::formatMoney($model->invoice->invoice_subtotal,0,',','.', Product::CURRENCY_DOLAR)?>
                                                    <?php endif; ?>
                                                <?php endif; ?>
                                            </span></strong>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan="4" class="text-right"><span><b><?= (new SalesInvoice)->getAttributeLabel('invoice_disc_amount')?></b></span></td>
                                        <td>
                                            <div class="row">
                                                <div class="col-md-4">
                                                    <span class="detail-invoice-disc-percent"><?= isset($model->invoice->invoice_disc_percent) ? $model->invoice->invoice_disc_percent : 0?> %</span>
                                                </div>
                                                <div class="col-md-8 text-right">
                                                     <span class="detail-invoice-disc-amount">
                                                        <?php if(isset($model->invoice->salesInvoiceItems)): ?>
                                                            <?php if($model->invoice->invoice_exchange_rate > 1): ?>
                                                                <?= CurrencyComponent::formatMoney($model->invoice->invoice_disc_amount) ?> 
                                                            <?php else: ?>
                                                                <?= CurrencyComponent::formatMoney($model->invoice->invoice_disc_amount,0,',','.', Product::CURRENCY_DOLAR) ?> 
                                                            <?php endif; ?>
                                                        <?php endif; ?>
                                                     </span>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan="4" class="text-right"></td>
                                        <td class="text-right">
                                             <span class="detail-invoice-disc-percent2">
                                                 <?php if(isset($model->invoice->salesInvoiceItems)): ?>
                                                    <?php if($model->invoice->invoice_exchange_rate > 1): ?>
                                                        <?= CurrencyComponent::formatMoney($model->invoice->invoice_disc_amount2) ?> 
                                                    <?php else: ?>
                                                        <?= CurrencyComponent::formatMoney($model->invoice->invoice_disc_amount2,0,',','.', Product::CURRENCY_DOLAR) ?> 
                                                    <?php endif; ?>
                                                <?php endif; ?>
                                            </span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan="4" class="text-right"><span><b><?= (new SalesInvoice)->getAttributeLabel('invoice_tax_amount')?></b></span></td>
                                        <td>
                                            <div class="row">
                                                <div class="col-md-4">
                                                    <span class="detail-invoice-tax-percent"><?= isset($model->invoice->invoice_tax_percent) ? $model->invoice->invoice_tax_percent : 0?> %</span>
                                                </div>
                                                <div class="col-md-8 text-right">
                                                    <span class="detail-invoice-tax-amount">
                                                        <?php if(isset($model->invoice->salesInvoiceItems)): ?>
                                                            <?php if($model->invoice->invoice_exchange_rate > 1): ?>
                                                                <?= CurrencyComponent::formatMoney($model->invoice->invoice_tax_amount)?>
                                                            <?php else: ?>
                                                                <?= CurrencyComponent::formatMoney($model->invoice->invoice_tax_amount,0,',','.', Product::CURRENCY_DOLAR)?>
                                                            <?php endif; ?>
                                                        <?php endif; ?>
                                                    </span>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan="4" class="text-right"><span><b><?= (new SalesInvoice)->getAttributeLabel('invoice_shipping_cost')?></b></span></td>
                                        <td class="text-right">
                                             <span class="detail-invoice-shipping-cost">
                                                <?php if(isset($model->invoice->salesInvoiceItems)): ?>
                                                    <?php if($model->invoice->invoice_exchange_rate > 1): ?>
                                                        <?= CurrencyComponent::formatMoney($model->invoice->invoice_shipping_cost)?>
                                                    <?php else: ?>
                                                        <?= CurrencyComponent::formatMoney($model->invoice->invoice_shipping_cost,0,',','.', Product::CURRENCY_DOLAR)?>
                                                    <?php endif; ?>
                                                <?php endif; ?>
                                            </span> <br>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan="4" class="text-right"><span><b><?= (new SalesInvoice)->getAttributeLabel('invoice_grand_total')?></b></span></td>
                                        <td class="text-right">
                                            <strong><span class="detail-invoice-grand-total">
                                                <?php if(isset($model->invoice->salesInvoiceItems)): ?>
                                                    <?php if($model->invoice->invoice_exchange_rate > 1): ?>
                                                        <?= CurrencyComponent::formatMoney($model->invoice->invoice_grand_total)?>
                                                    <?php else: ?>
                                                        <?= CurrencyComponent::formatMoney($model->invoice->invoice_grand_total,0,',','.', Product::CURRENCY_DOLAR)?>
                                                    <?php endif; ?>
                                                <?php endif; ?>
                                            </span></strong>
                                        </td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<section class="row payment-session">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-content">
                <div class="card-body padding-top-zero">
                   <div class="row">
                        <div class="grand-header">
                            <center>
                                <span><b>Total Nota</b></span>
                                <input type="hidden" class="grandtotal" value="<?= isset($model->invoice->invoice_grand_total) ? $model->invoice->invoice_grand_total : 0; ?>" >
                                <h2><b><p class="grandtotal-label">$<?= isset($model->invoice->invoice_grand_total) ? $model->invoice->invoice_grand_total : 0;?></p></b></h2>
                            </center>
                        </div>
                        <div class="paid-header">
                            <center>
                                <span><b>Total Terbayar</b></span>
                                <input type="hidden" class="paid" value="<?= isset($model->invoice) ? $model->getInvoicePaidAmount($model->invoice_id, $model->invoice->customer_id) : 0 ?>">
                                <h2><b><span class="paid-label"><?= isset($model->invoice) ? $model->getInvoicePaidAmount($model->invoice_id, $model->invoice->customer_id) : '$0';?></span></b></h2>
                            </center>
                        </div>
                        <div class="mustpay-header">
                            <center>
                                <span><b>Total Harus Dibayar</b></span>
                                <input type="hidden" class="mustpay" value="<?= isset($model->invoice->invoice_grand_total) ? $model->invoice->invoice_grand_total : 0;?>">
                                <h2><b><span class="mustpay-label">$<?= isset($model->invoice->invoice_grand_total) ? $model->invoice->invoice_grand_total : 0;?></span></b></h2>
                            </center>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="row-template" style="display: none">
                                <table class="table-template">
                                    <tr id="row-{index}" class="row-item">
                                        <td>
                                            <?= Html::dropDownList('item[{index}][payment_detail_method]', '', (new SalesPaymentDetail)->getPayMethodLabel(), ['class' => 'form-control input-sm payment-detail-method']) ?>
                                            <strong><span class="bank-label"><?= (new SalesPaymentDetail)->getAttributeLabel('bank_id')?></span></strong>
                                            <?= Html::dropDownList('item[{index}][bank_id]', '', ArrayHelper::map(Bank::find()->where('is_deleted=:is', [':is' => 0])->orderBy(['bank_acc_name' => SORT_ASC])->all(), 'id', 'bankName'), ['class' => 'form-control input-sm bank-id', 'prompt' => 'Pilih Bank']) ?>
                                            <?=Html::textInput('item[{index}][payment_detail_bank_acc_name]', '', array('class' => 'form-control bank-acc-name input-sm'));?>
                                            <?=Html::textInput('item[{index}][payment_detail_bank_acc_number]', '', array('class' => 'form-control bank-acc-number input-sm'));?>
                                            <strong><span class="credit-label"><?= (new SalesPaymentDetail)->getAttributeLabel('payment_detail_creditcard_number')?></span></strong>
                                            <?=Html::textInput('item[{index}][payment_detail_creditcard_number]', '', array('class' => 'form-control creditcard-number input-sm'));?>
                                            <strong><span class="debit-label"><?= (new SalesPaymentDetail)->getAttributeLabel('payment_detail_debitcard_number')?></span></strong>
                                            <?=Html::textInput('item[{index}][payment_detail_debitcard_number]', '', array('class' => 'form-control debitcard-number input-sm'));?>
                                        </td>
                                        <td>
                                            <?=Html::textInput('item[{index}][payment_detail_amount]', '', array('class' => 'form-control payment-detail-amount angka input-sm'));?>
                                        </td>
                                        <td><a href="#" class="btn btn-outline-danger btn-sm btn-remove"><i class="la la-trash"></i></a></td>
                                    </tr>
                                </table>
                            </div>
                            <div class="table-responsive">
                                <table class="table table-striped table-bordered table-item-payment">
                                    <thead>
                                        <tr>
                                            <th style="width: 40%;"><?= (new SalesPaymentDetail)->getAttributeLabel('payment_detail_method')?></th>
                                            <th style="width: 40%;"><?= (new SalesPaymentDetail)->getAttributeLabel('payment_detail_amount')?></th>
                                            <th style="width: 10%;"></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    <?php if (empty($items)):?>
                                    <tr class="row-item" id="row-0">
                                        <td>
                                            <?= Html::dropDownList('item[0][payment_detail_method]', '', (new SalesPaymentDetail)->getPayMethodLabel(), ['class' => 'form-control input-sm payment-detail-method']) ?>
                                            <strong><span class="bank-label"><?= (new SalesPaymentDetail)->getAttributeLabel('bank_id')?></span></strong>
                                            <?= Html::dropDownList('item[0][bank_id]', '', ArrayHelper::map(Bank::find()->where('is_deleted=:is', [':is' => 0])->orderBy(['bank_acc_name' => SORT_ASC])->all(), 'id', 'bankName'), ['class' => 'form-control input-sm bank-id', 'prompt' => 'Pilih Bank']) ?>
                                            <?=Html::textInput('item[0][payment_detail_bank_acc_name]', '', array('class' => 'form-control bank-acc-name input-sm'));?>
                                            <?=Html::textInput('item[0][payment_detail_bank_acc_number]', '', array('class' => 'form-control bank-acc-number input-sm'));?>
                                            <strong><span class="credit-label"><?= (new SalesPaymentDetail)->getAttributeLabel('payment_detail_creditcard_number')?></span></strong>
                                            <?=Html::textInput('item[0][payment_detail_creditcard_number]', '', array('class' => 'form-control creditcard-number input-sm'));?>
                                            <strong><span class="debit-label"><?= (new SalesPaymentDetail)->getAttributeLabel('payment_detail_debitcard_number')?></span></strong>
                                            <?=Html::textInput('item[0][payment_detail_debitcard_number]', '', array('class' => 'form-control debitcard-number input-sm'));?>
                                        </td>
                                        <td>
                                            <?=Html::textInput('item[0][payment_detail_amount]', '', array('class' => 'form-control payment-detail-amount angka input-sm'));?>
                                        </td>
                                        <td></td>
                                    </tr>
                                    <?php endif; ?>
                                    <?php if (!empty($items)):?>
                                        <?php foreach($items as $i => $item):?>
                                            <?php if ($i !== "{index}"):?>
                                        <tr id="row-<?=$i?>" class="row-item">
                                            <td>
                                                <?= Html::dropDownList('item['.$i.'][payment_detail_method]', $item['payment_detail_method'], (new SalesPaymentDetail)->getPayMethodLabel(), ['class' => 'form-control input-sm payment-detail-method']) ?>
                                                <strong><span class="bank-label"><?= (new SalesPaymentDetail)->getAttributeLabel('bank_id')?></span></strong>
                                                <?= Html::dropDownList('item['.$i.'][bank_id]', $item['bank_id'], ArrayHelper::map(Bank::find()->where('is_deleted=:is', [':is' => 0])->orderBy(['bank_acc_name' => SORT_ASC])->all(), 'id', 'bankName'), ['class' => 'form-control input-sm bank-id', 'prompt' => 'Pilih Bank']) ?>
                                                <?=Html::textInput('item['.$i.'][payment_detail_bank_acc_name]', $item['payment_detail_bank_acc_name'], array('class' => 'form-control bank-acc-name input-sm'));?>
                                                <?=Html::textInput('item['.$i.'][payment_detail_bank_acc_number]', $item['payment_detail_bank_acc_number'], array('class' => 'form-control bank-acc-number input-sm'));?>
                                                <strong><span class="credit-label"><?= (new SalesPaymentDetail)->getAttributeLabel('payment_detail_creditcard_number')?></span></strong>
                                                <?=Html::textInput('item['.$i.'][payment_detail_creditcard_number]', $item['payment_detail_creditcard_number'], array('class' => 'form-control creditcard-number input-sm'));?>
                                                <strong><span class="debit-label"><?= (new SalesPaymentDetail)->getAttributeLabel('payment_detail_debitcard_number')?></span></strong>
                                                <?=Html::textInput('item['.$i.'][payment_detail_debitcard_number]', $item['payment_detail_debitcard_number'], array('class' => 'form-control debitcard-number input-sm'));?>
                                            </td>
                                            <td>
                                                <?=Html::textInput('item['.$i.'][payment_detail_amount]', $item['payment_detail_amount'], array('class' => 'form-control payment-detail-amount angka input-sm'));?>
                                            </td>
                                            <td><a href="#" class="btn btn-outline-danger btn-sm btn-remove"><i class="la la-trash"></i></a></td>
                                        </tr>
                                            <?php endif;?>
                                        <?php endforeach;?>
                                    <?php endif;?>
                                    <tr class="row-footer-button-add">
                                        <td colspan="2">
                                            <button href="javascript:;" onclick="" type="button" class="btn btn-outline-info btn-sm btn-add-row"><i class="la la-plus-circle"></i> Tambah baris</button>
                                        </td>
                                        <td></td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-12">
                            <center>
                                <span><b>Total Bayar</b></span>
                                <input type="hidden" name="SalesPayment[payment_total_amount]" class="payment-total-amount">
                                <h2><b><span class="payment-total-amount-label">$0</span></b></h2>
                            </center>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-12">
                            <center>
                                <span><b>Kembalian</b></span>
                                <input type="hidden" class="change" value="">
                                <h2><b><span class="change-label">$0</span></b></h2>
                            </center>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-12">
                            <center>
                                <span><b>Sisa Bayar</b></span>
                                <input type="hidden" class="outstanding" value="">
                                <h2><b><span class="outstanding-label">$0</span></b></h2>
                            </center>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<?php ActiveForm::end(); ?>
<script>
function addRow()
{
    var index = 0;
    if (typeof $(".table-item-payment .row-item").last().attr("id") != 'undefined')
        index = parseInt($(".table-item-payment .row-item").last().attr("id").split("-")[1]) + 1;
        
    var clone = $(".table-template .row-item").clone().attr("id", "row-"+index);
    
    clone.html(function(i, oldTr) {return oldTr.replace(/\{index}/g, index);});
    
    $(".row-footer-button-add").before(clone);
}

function dollarToRupiah(kurs, amount){
    
    var temp = parseFloat(kurs) * parseFloat(amount);
    return temp;

}

function mustpayAmount(){
    var grandtotal = $('.grandtotal').val();
    var paid = $('.paid').val();

    var mustpay = parseFloat(grandtotal) - parseFloat(paid);
    $('.mustpay').val(mustpay);
    $('.mustpay-label').html("Rp"+number_format(mustpay, 0, ',', '.'));
}

function togglePayment(){
    if($('#salespayment-invoice_id').val() == '' || $('#salespayment-invoice_id').val() == 0)
        $('.payment-session').hide();
    else
        $('.payment-session').show();
}

function bankFieldHide(){
    $('.bank-acc-name').hide();
    $('.bank-acc-number').hide();
}

function methodFieldHide(){
    $('.bank-label').hide();
    $('.bank-id').hide();
    $('.credit-label').hide();
    $('.creditcard-number').hide();
    $('.debit-label').hide();
    $('.debitcard-number').hide();
}

function round(v) {
    return (v >= 0 || -1) * Math.round(Math.abs(v));
}

function total(){
    var n = $('.table-item-payment .payment-detail-amount').length;
    var mustpay = $('.mustpay').val();
    var hsl = $(".table-item-payment .payment-detail-amount");
    var total = 0;
    for(var i = 0; i < n; i++){
        total = total + Number($(hsl[i]).val());
        // alert(total);
    }
    // alert(total);
    
    $('.payment-total-amount').val(total);
    $('.payment-total-amount-label').html("Rp"+number_format(total, 0, ',', '.'));
    
    var change = parseFloat(total) - parseFloat(mustpay);
    if(change < 0)
        change = round(change);
    else
        change = Math.floor(change);

    $('.change').val(change);
    $('.change-label').html("Rp"+number_format(change, 0, ',', '.'));
    var outstanding = parseFloat(mustpay) - parseFloat(total);
    $('.outstanding').val(outstanding);
    $('.outstanding-label').html("Rp"+number_format(outstanding, 0, ',', '.'));
    // var sisa = 0;
    // if(total <= $('#mustpay').val()){
    //     sisa = $('#mustpay').val() - total;
    // }else{
    //     $('#totalbayar').val($('#mustpay').val());
    //     sisa = $('#mustpay').val() - total;
    // }
    // $('#sisa-bayar').html("Rp"+number_format(sisa, 0, ',', '.'));
}

function checkHeader(){
    if($('.paid').val() != 0){
        $('.paid-header').show();
        $('.paid-header').addClass('col-lg-4');
        $('.grand-header').removeClass('col-lg-6').addClass('col-lg-4');
        $('.mustpay-header').removeClass('col-lg-6').addClass('col-lg-4');
    }
    else{
        $('.paid-header').hide();
        $('.grand-header').addClass('col-lg-6');
        $('.mustpay-header').addClass('col-lg-6');
    }
    
}


$(document).on('click', '.btn-remove', function(e){

    var tr = $(this).closest('tr');
    tr.remove();
    total();
    e.preventDefault();

});

$(document).on('change', '.payment-detail-method', function(e){
    var index = $(this).val();
    var tr = $(this).closest('tr');
    
    if(index == 'banktransfer'){
        tr.find(".bank-label").show();
        tr.find(".bank-id").show();
        tr.find('.credit-label').hide();
        tr.find('.creditcard-number').hide();
        tr.find('.debit-label').hide();
        tr.find('.debitcard-number').hide();
    }
    else if(index == 'creditcardbca' || index == 'creditcardmandiri' || index == 'creditcardbri' || index == 'creditcardbni'){
        tr.find(".bank-label").hide();
        tr.find(".bank-id").hide();
        tr.find('.credit-label').show();
        tr.find('.creditcard-number').show();
        tr.find('.debit-label').hide();
        tr.find('.debitcard-number').hide();
    }
    else if(index == 'debitbca' || index == 'debitmandiri' || index == 'debitbri' || index == 'debitbni'){
        tr.find(".bank-label").hide();
        tr.find(".bank-id").hide();
        tr.find('.credit-label').hide();
        tr.find('.creditcard-number').hide();
        tr.find('.debit-label').show();
        tr.find('.debitcard-number').show();
    }
    else{
        tr.find(".bank-label").hide();
        tr.find(".bank-id").hide();
        tr.find('.credit-label').hide();
        tr.find('.creditcard-number').hide();
        tr.find('.debit-label').hide();
        tr.find('.debitcard-number').hide();
    }

});

$(document).on("change", ".bank-id", function(e){
    var id = $(this).val();
    var tr = $(this).closest('tr');
    var url = $('#baseUrl').val();
    $number = tr.find('.bank-acc-number');
    $name = tr.find('.bank-acc-name');
    // console.log(id);
    $.get(url+'/getitem',{id:$(this).val()},function(response){
        let data = $.parseJSON(response);
        // console.log(data.bank_acc_name); 
        $number.val(data.bank_acc_number);
        $name.val(data.bank_acc_name);
    });
});

$(document).on('input', '.payment-detail-amount', function(e){
    total();
});

$(document).on('change', '#salespayment-customer_id', function(){
    var id = $(this).val();
    var url = $('#baseUrl').val();

    $.get(url+'/getinvoice',{id:id},function(response){
        let data = $.parseJSON(response);
        $('#salespayment-invoice_id').empty();
        $('#salespayment-invoice_id').append(
            '<option value="0">Pilih Nota</option>'
        );
        $.each(data, function(key, value){
            $('#salespayment-invoice_id').append(
                    '<option value='+data[key].id+'>'+data[key].invoice_code+'</option>'
              );
        });

    });
});

$(document).on('change', '#salespayment-invoice_id', function(){
    var id = $(this).val();
    var url = $('#baseUrl').val();
    if(id != 0){
        $.get(url+'/getdetailinvoice',{id:id},function(response){
            let data = $.parseJSON(response);
            $('.detail-invoice-code').html(data.invoice_code);
            $('.detail-invoice-date').html(data.invoice_date);
            $('.detail-invoice-customer').html(data.customer_name);
            $('.detail-invoice-salesman').html(data.salesman_name);
            $('.detail-invoice-disc-percent').html(data.invoice_disc_percent+' %');
            $('.detail-invoice-comission').html(data.comission_type_name);
            $('.detail-invoice-tax-percent').html(data.invoice_tax_percent+ ' %');
            $('.detail-invoice-exchangerate-label').html(data.invoice_exchange_rate);
            $('.detail-invoice-exchangerate').val(data.invoice_exchange_rate);
            $('.detail-invoice-comissiondate').html(data.invoice_comission_pay_date);
            $('.grandtotal').val(data.invoice_grand_total);
            $('.detail-invoice-comissionvalue').html('$'+format_usd(data.invoice_comission_value));
            $('.grandtotal-label').html("Rp"+number_format(data.invoice_grand_total, 0, ',', '.'));
            $('.paid-label').html("Rp"+number_format(data.customer_paid_amount, 0, ',', '.'));

            // $('.prevInvoice').val(data.customer_debt_amount);
            // $('.prevInvoice-label').html('$'+format_usd(data.customer_debt_amount));
            $('.paid').val(data.customer_paid_amount);
            if(data.invoice_exchange_rate > 1){
                $('.detail-invoice-subtotal').html("Rp"+number_format(data.invoice_subtotal, 0, ',', '.'));
                $('.detail-invoice-disc-amount').html("Rp"+number_format(data.invoice_disc_amount, 0, ',', '.'));
                $('.detail-invoice-tax-amount').html("Rp"+number_format(data.invoice_tax_amount, 0, ',', '.'));
                $('.detail-invoice-shipping-cost').html("Rp"+number_format(data.invoice_shipping_cost, 0, ',', '.'));
                $('.detail-invoice-grand-total').html("Rp"+number_format(data.invoice_grand_total, 0, ',', '.'));
                $('.detail-invoice-disc-percent2').html("Rp"+number_format(data.invoice_disc_amount2, 0, ',', '.'));
                $('#salespayment-payment_exchange_rate').val(data.invoice_exchange_rate);
            }
            else{
                $('.detail-invoice-subtotal').html('$'+format_usd(data.invoice_subtotal));
                $('.detail-invoice-disc-amount').html('$'+format_usd(data.invoice_disc_amount));
                $('.detail-invoice-tax-amount').html('$'+format_usd(data.invoice_tax_amount));
                $('.detail-invoice-shipping-cost').html('$'+format_usd(data.invoice_shipping_cost));
                $('.detail-invoice-grand-total').html('$'+format_usd(data.invoice_grand_total));
                $('.detail-invoice-disc-percent2').html('$'+format_usd(data.invoice_disc_amount2));
                $('#salespayment-payment_exchange_rate').val(1);
            }

            mustpayAmount();
            itemDetail();
            total();
            checkHeader();
            
        });
    }
    togglePayment();
});

function itemDetail()
{
    var id = $("#salespayment-invoice_id").val();
    var url = $('#baseUrl').val();
    var item = "";

    $.get(url+'/getiteminvoice',{id:$("#salespayment-invoice_id").val()},function(response){
        let data = $.parseJSON(response);
        
        $('.table-item .row-detail-invoice').remove();
        
        for(var i=0; i<data.length;i++){
            item += '<tr class="row-detail-invoice">'+
            // '<td>'+data[i].invoice_product_code+'</td>'+
            '<td><center>'+data[i].invoice_product_code+' -- '+data[i].invoice_product_name+'</center></td>'+
            '<td class="text-right">'+data[i].invoice_item_qty+'</td>'+
            '<td class="text-right">'+"$"+format_usd(data[i].invoice_item_disc_amount)+'</td>'+
            '<td class="text-right">'+"$"+format_usd(data[i].invoice_item_price)+'</td>'+
            '<td class="text-right">'+data[i].invoice_item_row_total+'</td>'+
        '</tr>';
        }
        $('.table-item').prepend(item);
    });
}

function toggleExchange(){
    var exchange = $('#salespayment-payment_exchange_rate').val();
    var tmptotal = $('.detail-invoice-exchangerate').val() == 1 ? dollarToRupiah(exchange, $('.grandtotal').val()) : $('.grandtotal').val();
    var tmpmustpay = $('.detail-invoice-exchangerate').val() == 1 ? dollarToRupiah(exchange, $('.mustpay').val()) : $('.mustpay').val();
    var tmppaid = $('.detail-invoice-exchangerate').val() == 1 ? dollarToRupiah(exchange, $('.paid').val()) : $('.paid').val();
    
    $('.grandtotal').val(tmptotal);
    $('.grandtotal-label').html("Rp"+number_format(tmptotal, 0, ',', '.'));
    $('.mustpay').val(tmpmustpay);
    $('.mustpay-label').html("Rp"+number_format(tmpmustpay, 0, ',', '.'));
    $('.paid').val(tmppaid);
    $('.paid-label').html("Rp"+number_format(tmppaid, 0, ',', '.'));
}

$(document).on('change', '#salespayment-payment_exchange_rate', function(){
    if($('.detail-invoice-exchangerate').val() == 1){
        
        toggleExchange();
        total();
    }
});

$(document).ready(function(){
    bankFieldHide();
    methodFieldHide();
    toggleExchange();
    total();
    togglePayment();

    $(".btn-add-row").click(function(){
        addRow();
    });

    checkHeader();
});

</script>