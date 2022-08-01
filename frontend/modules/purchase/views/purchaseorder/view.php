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
use common\models\PurchaseOrder;
use common\models\PurchaseOrderItem;


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
				<h4 class="card-title">Purchase Order</h4>
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
		            		<div class="col-lg-4"><b><?=$model->getAttributeLabel('purchase_order_code')?></b></div>
		            		<div class="col-lg-1">:</div>
		            		<div class="col-lg-5"><?=isset($model->purchase_order_code) ? $model->purchase_order_code : '' ?></div>
	                	</div>	
	                	<div class="row mb-1">
		            		<div class="col-lg-4"><b><?=$model->getAttributeLabel('purchase_order_date')?></b></div>
		            		<div class="col-lg-1">:</div>
		            		<div class="col-lg-5"><?=isset($model->purchase_order_date) ? DateComponent::getHumanizedDate($model->purchase_order_date) : '' ?></div>
	                	</div>
	                </div>
	                <div class="col-lg-6">
                        <div class="row mb-1">
                            <div class="col-lg-4"><b><?=$model->getAttributeLabel('supplier_id')?></b></div>
                            <div class="col-lg-1">:</div>
                            <div class="col-lg-5"><?=$model->supplier->supplier_name ?></div>
                        </div>
	                	<div class="row mb-1">
		            		<div class="col-lg-4"><b><?=$model->getAttributeLabel('purchase_order_notes')?></b></div>
		            		<div class="col-lg-1">:</div>
		            		<div class="col-lg-5"><?=$model->purchase_order_notes?></div>
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
                                            <th style="width: 20%"><center><?= (new PurchaseOrderItem)->getAttributeLabel('product_id')?></center></th>
                                            <th style="width: 10%"><center><?= (new PurchaseOrderItem)->getAttributeLabel('purchase_order_item_qty')?></center></th>
                                            <th style="width: 15%;"><center><?= (new PurchaseOrderItem)->getAttributeLabel('purchase_order_item_disc_amount')?></center></th>
                                            <th style="width: 15%;"><center><?= (new PurchaseOrderItem)->getAttributeLabel('purchase_order_item_disc_percent')?></center></th>
                                            <th style="width: 20%"><center><?= (new PurchaseOrderItem)->getAttributeLabel('purchase_order_item_price')?></center></th>
                                            <th style="width: 20%;"><center><?= (new PurchaseOrderItem)->getAttributeLabel('purchase_order_item_row_total')?></center></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach($model->purchaseOrderItems as $item):?>
                                        <tr class="row-item">
                                            <td>
                                                <center><span class="product-name"><?= $item->product->product_name ?></span></center>
                                            </td>
                                            <td class="text-right">
                                                <span><?= $item->purchase_order_item_qty ?></span>
                                            </td>
                                            <td class="text-right">
                                                <?= CurrencyComponent::formatMoney($item->purchase_order_item_disc_amount,0,',','.', Product::CURRENCY_DOLAR) ?>
                                            </td>
                                            <td class="text-right">
                                                <?= $item->purchase_order_item_disc_percent ?> %
                                            </td>
                                            <td class="text-right">
                                                <?= CurrencyComponent::formatMoney($item->purchase_order_item_price,0,',','.', Product::CURRENCY_DOLAR) ?>
                                            </td>
                                            <td class="text-right">
                                                <?= CurrencyComponent::formatMoney($item->purchase_order_item_row_total,0,',','.', Product::CURRENCY_DOLAR) ?>
                                            </td>
                                        </tr>
                                        <?php endforeach;?>
                                    <tr>
                                        <td colspan="5" class="text-right"><span><b><?= (new PurchaseOrder)->getAttributeLabel('purchase_order_subtotal')?></b></span></td>
                                        <td class="text-right">
                                            <strong><span class="invoice-subtotal-label"><?= CurrencyComponent::formatMoney($model->purchase_order_subtotal,0,',','.', Product::CURRENCY_DOLAR) ?></span></strong>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan="5" class="text-right"><span><b><?= (new PurchaseOrder)->getAttributeLabel('purchase_order_disc_amount')?></b></span></td>
                                        <td class="text-right">
                                             <?= CurrencyComponent::formatMoney($model->purchase_order_disc_amount,0,',','.', Product::CURRENCY_DOLAR) ?> <br>
                                             <?= $model->purchase_order_disc_percent ?> %

                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan="5" class="text-right"><span><b><?= (new PurchaseOrder)->getAttributeLabel('purchase_order_grand_total')?></b></span></td>
                                        <td class="text-right">
                                            <strong><span class="invoice-grand-total"><?= CurrencyComponent::formatMoney($model->purchase_order_grand_total,0,',','.', Product::CURRENCY_DOLAR) ?></span></strong>
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
