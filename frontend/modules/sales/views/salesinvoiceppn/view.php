<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use app\components\BaseController;
use frontend\components\LabelComponent;
use frontend\components\AccessComponent;
use common\components\DateComponent;
use common\components\CurrencyComponent;
use frontend\components\ButtonComponent;
use common\models\Product;
use common\models\SalesInvoice;
use common\models\SalesInvoiceItem;


$this->title = BaseController::getCustomPageTitle(BaseController::$page_caption);

$toolbar[] = ButtonComponent::getBackButton();
    
BaseController::$toolbar = $toolbar;
    
foreach(Yii::$app->session->getAllFlashes() as $key => $message)
    echo '<div class="alert alert-' . $key . '">' . $message . "</div>\n";

?>
<section class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">Invoice PPN</h4>
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
                    <div class="col-lg-4">
                        <div class="row mb-1">
                            <div class="col-lg-4"><b><?=$model->getAttributeLabel('invoice_code')?></b></div>
                            <div class="col-lg-1">:</div>
                            <div class="col-lg-5"><?=isset($model->invoice_code) ? $model->invoice_code : '' ?></div>
                        </div>
                        <div class="row mb-1">
                            <div class="col-lg-4"><b><?=$model->getAttributeLabel('invoice_number_document')?></b></div>
                            <div class="col-lg-1">:</div>
                            <div class="col-lg-5"><?=isset($model->invoice_number_document) ? $model->invoice_number_document : '' ?></div>
                        </div>
                        <div class="row mb-1">
                            <div class="col-lg-4"><b><?=$model->getAttributeLabel('invoice_type')?></b></div>
                            <div class="col-lg-1">:</div>
                            <div class="col-lg-5"><?=isset($model->invoice_type) ? $model->invoice_type : '' ?></div>
                        </div>
                        <div class="row mb-1">
                            <div class="col-lg-4"><b><?=$model->getAttributeLabel('invoice_date')?></b></div>
                            <div class="col-lg-1">:</div>
                            <div class="col-lg-5"><?=isset($model->invoice_date) ? $model->invoice_date : '' ?></div>
                        </div>  
                        <div class="row mb-1">
                            <div class="col-lg-4"><b><?=$model->getAttributeLabel('salesman_id')?></b></div>
                            <div class="col-lg-1">:</div>
                            <div class="col-lg-5"><?= $model->salesman->employee_name?></div>
                        </div>
                        <div class="row mb-1">
                            <div class="col-lg-4"><b><?=$model->getAttributeLabel('customer_id')?></b></div>
                            <div class="col-lg-1">:</div>
                            <div class="col-lg-5"><?=$model->customer->customerName ?></div>
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="row mb-1">
                            <div class="col-lg-4"><b><?=$model->getAttributeLabel('comission_type_id')?></b></div>
                            <div class="col-lg-1">:</div>
                            <div class="col-lg-5"><?=isset($model->comissionType->comission_type_name) ? $model->comissionType->comission_type_name : ''?></div>
                        </div>
                        <div class="row mb-1">
                            <div class="col-lg-4"><b><?=$model->getAttributeLabel('invoice_comission_value')?></b></div>
                            <div class="col-lg-1">:</div>
                            <div class="col-lg-5">
                                <?php if(isset($model->invoice_comission_value)): ?>
                                        <?= CurrencyComponent::formatMoney($model->invoice_comission_value,0,',','.', Product::CURRENCY_DOLAR)?>
                                <?php endif; ?>
                            </div>
                        </div>
                        <div class="row mb-1">
                            <div class="col-lg-4"><b><?=$model->getAttributeLabel('invoice_exchange_rate')?></b></div>
                            <div class="col-lg-1">:</div>
                            <div class="col-lg-5"><?=$model->invoice_exchange_rate?></div>
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="row mb-1">
                            <div class="col-lg-4"><b><?=$model->getAttributeLabel('invoice_receiver')?></b></div>
                            <div class="col-lg-1">:</div>
                            <div class="col-lg-5"><?=isset($model->invoice_receiver) ? $model->invoice_receiver : ''?></div>
                        </div>
                        <div class="row mb-1">
                            <div class="col-lg-4"><b><?=$model->getAttributeLabel('invoice_destination_address')?></b></div>
                            <div class="col-lg-1">:</div>
                            <div class="col-lg-5"><?=isset($model->invoice_destination_address) ? $model->invoice_destination_address : ''?></div>
                        </div>
                        <div class="row mb-1">
                            <div class="col-lg-4"><b><?=$model->getAttributeLabel('invoice_postal_code')?></b></div>
                            <div class="col-lg-1">:</div>
                            <div class="col-lg-5"><?=isset($model->invoice_postal_code) ? $model->invoice_postal_code : ''?></div>
                        </div>
                        <div class="row mb-1">
                            <div class="col-lg-4"><b><?=$model->getAttributeLabel('invoice_destination_city')?></b></div>
                            <div class="col-lg-1">:</div>
                            <div class="col-lg-5"><?=isset($model->invoice_destination_city) ? $model->invoice_destination_city : ''?></div>
                        </div>
                        <div class="row mb-1">
                            <div class="col-lg-4"><b><?=$model->getAttributeLabel('invoice_destination_province')?></b></div>
                            <div class="col-lg-1">:</div>
                            <div class="col-lg-5"><?=isset($model->invoice_destination_province) ? $model->invoice_destination_province : ''?></div>
                        </div>
                    </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<section class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">Detail</h4>
                <a class="heading-elements-toggle"><i class="la la-ellipsis-v font-medium-3"></i></a>
                <div class="heading-elements">
                    <ul class="list-inline mb-0">
                        <li><a data-action="collapse"><i class="ft-minus"></i></a></li>
                    </ul>
                </div>
            </div>
            <div class="card-content">
                <div class="card-body padding-top-zero">
                    <div class="row">
                        <div class="col-lg-12">       
                            <div class="table-responsive">
                                <table class="table table-striped table-bordered table-item">
                                    <thead>
                                        <tr>
                                            <th style="width: 25%"><center><?= (new SalesInvoiceItem)->getAttributeLabel('product_id')?></center></th>
                                            <th style="width: 10%"><center><?= (new SalesInvoiceItem)->getAttributeLabel('invoice_item_qty')?></center></th>
                                            <th style="width: 15%"><center><?= (new SalesInvoiceItem)->getAttributeLabel('packaging_id')?></center></th>
                                            <th style="width: 15%;"><center><?= (new SalesInvoiceItem)->getAttributeLabel('invoice_item_disc_amount')?></center></th>
                                            <th style="width: 15%"><center><?= (new SalesInvoiceItem)->getAttributeLabel('invoice_item_price')?></center></th>
                                            <th style="width: 20%;"><center><?= (new SalesInvoiceItem)->getAttributeLabel('invoice_item_row_total')?></center></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach($model->salesInvoiceItems as $item):?>
                                        <tr class="row-item">
                                            <td>
                                                <center><span class="product-name"><?= $item->product->product_name ?></span></center>
                                            </td>
                                            <td class="text-right">
                                                <span><?= $item->invoice_item_qty ?></span>
                                            </td>
                                            <td>
                                                <span><center><?= $item->packaging->packaging_name ?></center></span>
                                            </td>
                                            <td class="text-right">
                                                <?= CurrencyComponent::formatMoney($item->invoice_item_disc_amount,0,',','.', Product::CURRENCY_DOLAR) ?>
                                            </td>
                                            <td class="text-right">
                                                <?= CurrencyComponent::formatMoney($item->invoice_item_price,0,',','.', Product::CURRENCY_DOLAR) ?>
                                            </td>
                                            <td class="text-right">
                                                <?php if($model->invoice_exchange_rate > 1): ?>
                                                    <?= CurrencyComponent::formatMoney($item->invoice_item_row_total) ?>
                                                <?php else: ?>
                                                    <?= CurrencyComponent::formatMoney($item->invoice_item_row_total,0,',','.', Product::CURRENCY_DOLAR) ?>
                                                <?php endif; ?>
                                            </td>
                                        </tr>
                                        <?php endforeach;?>
                                    <tr>
                                        <td colspan="5" class="text-right"><span><b><?= (new SalesInvoice)->getAttributeLabel('invoice_subtotal')?></b></span></td>
                                        <td class="text-right">
                                            <strong><span class="invoice-subtotal-label">
                                                <?php if($model->invoice_exchange_rate > 1): ?>
                                                    <?= CurrencyComponent::formatMoney($model->invoice_subtotal) ?>
                                                <?php else: ?>
                                                    <?= CurrencyComponent::formatMoney($model->invoice_subtotal,0,',','.', Product::CURRENCY_DOLAR) ?>
                                                <?php endif; ?>
                                            </span></strong>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan="5" class="text-right"><span><b><?= (new SalesInvoice)->getAttributeLabel('invoice_disc_amount')?></b></span></td>
                                        <td>
                                            <div class="row">
                                                <div class="col-md-4">
                                                    <?= $model->invoice_disc_percent ?> %
                                                </div>
                                                <div class="col-md-8 text-right">
                                                    <?php if($model->invoice_exchange_rate > 1): ?>
                                                        <?= CurrencyComponent::formatMoney($model->invoice_disc_amount) ?>
                                                    <?php else: ?>
                                                        <?= CurrencyComponent::formatMoney($model->invoice_disc_amount,0,',','.', Product::CURRENCY_DOLAR) ?>
                                                    <?php endif; ?>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan="5" class="text-right"></td>
                                        <td class="text-right">
                                                <?php if($model->invoice_exchange_rate > 1): ?>
                                                    <?= CurrencyComponent::formatMoney($model->invoice_disc_amount2) ?>
                                                <?php else: ?>
                                                    <?= CurrencyComponent::formatMoney($model->invoice_disc_amount2,0,',','.', Product::CURRENCY_DOLAR) ?>
                                                <?php endif; ?>

                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan="5" class="text-right"><span><b><?= (new SalesInvoice)->getAttributeLabel('invoice_tax_amount')?></b></span></td>
                                        <td>
                                            <div class="row">
                                                <div class="col-md-8">
                                                    <?= $model->invoice_tax_percent ?> %
                                                </div>
                                                <div class="col-md-4 text-right">
                                                    <?php if($model->invoice_exchange_rate > 1): ?>
                                                        <?= CurrencyComponent::formatMoney($model->invoice_tax_amount) ?> 
                                                    <?php else: ?>
                                                        <?= CurrencyComponent::formatMoney($model->invoice_tax_amount,0,',','.', Product::CURRENCY_DOLAR) ?> 
                                                    <?php endif; ?>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan="5" class="text-right"><span><b><?= (new SalesInvoice)->getAttributeLabel('invoice_shipping_cost')?></b></span></td>
                                        <td class="text-right">
                                                <?php if($model->invoice_exchange_rate > 1): ?>
                                                    <?= CurrencyComponent::formatMoney($model->invoice_shipping_cost) ?>
                                                <?php else: ?>
                                                    <?= CurrencyComponent::formatMoney($model->invoice_shipping_cost,0,',','.', Product::CURRENCY_DOLAR) ?>
                                                <?php endif; ?>
                                              <br>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan="5" class="text-right"><span><b><?= (new SalesInvoice)->getAttributeLabel('invoice_grand_total')?></b></span></td>
                                        <td class="text-right">
                                            <strong><span class="invoice-grand-total">
                                                <?php if($model->invoice_exchange_rate > 1): ?>
                                                    <?= CurrencyComponent::formatMoney($model->invoice_grand_total) ?>
                                                <?php else: ?>
                                                    <?= CurrencyComponent::formatMoney($model->invoice_grand_total,0,',','.', Product::CURRENCY_DOLAR) ?>
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
