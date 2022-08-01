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
use common\models\ComissionPayDetail;


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
				<h4 class="card-title">Pencairan Komisi</h4>
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
		            		<div class="col-lg-4"><b><?=$model->getAttributeLabel('comission_pay_date')?></b></div>
		            		<div class="col-lg-1">:</div>
		            		<div class="col-lg-5"><?=DateComponent::getHumanizedDate($model->comission_pay_date)?></div>
	                	</div>	
	                	<div class="row mb-1">
		            		<div class="col-lg-4"><b><?=$model->getAttributeLabel('salesman_id')?></b></div>
		            		<div class="col-lg-1">:</div>
		            		<div class="col-lg-5"><?= $model->salesman->employee_name ?></div>
	                	</div>	
	                	<div class="row mb-1">
		            		<div class="col-lg-4"><b><?=$model->getAttributeLabel('comission_pay_exchange_rate')?></b></div>
		            		<div class="col-lg-1">:</div>
		            		<div class="col-lg-5"><?=$model->comission_pay_exchange_rate ?></div>
	                	</div>
	                </div>
	                <div class="col-lg-6">
                        <div class="row mb-1">
                            <div class="col-lg-4"><b><?=$model->getAttributeLabel('comission_pay_periode')?></b></div>
                            <div class="col-lg-1">:</div>
                            <div class="col-lg-5"><?= $model->getPeriodeLabel($model->comission_pay_periode)?></div>
                        </div>
	                	<div class="row mb-1">
		            		<div class="col-lg-4"><b><?=$model->getAttributeLabel('comission_pay_value')?></b></div>
		            		<div class="col-lg-1">:</div>
		            		<div class="col-lg-5"><?= CurrencyComponent::formatMoney($model->comission_pay_value)?></div>
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
                                            <th style="width: 10%;"><center> <?= (new SalesInvoice)->getAttributeLabel('invoice_code')?></center></th>
                                            <th style="width: 20%;"><center><?= (new SalesInvoice)->getAttributeLabel('customer_name')?></center></th>
                                            <th style="width: 10%;"><center><?= (new SalesInvoice)->getAttributeLabel('invoice_date')?></center></th>
                                            <th style="width: 10%;"><center><?= (new SalesInvoice)->getAttributeLabel('invoice_comission_value')?></center></th>
                                            <th style="width: 20%;"><center><?= (new SalesInvoice)->getAttributeLabel('payment_date')?></center></th>
                                            <th style="width: 10%;"><center>Komisi Cair (%)</center></th>
                                            <th style="width: 20%;"><center>Subtotal</center></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                            foreach($model->comissionPayDetails as $item):
                                        ?>
                                        <tr class="row-item">
                                            <td>
                                                <span><?= $item->invoice->invoice_code?></span>
                                            </td>
                                            <td>
                                                <span><?= $item->invoice->customer->customerName?></span>
                                            </td>
                                            <td>
                                                <span><?= DateComponent::getHumanizedDate($item->invoice->invoice_date)?></span>
                                            </td>
                                            <td>
                                                <?= CurrencyComponent::formatMoney($item->invoice->invoice_comission_value,0,',','.', Product::CURRENCY_DOLAR) ?>
                                            </td>
                                            <td>
                                                <span><?= DateComponent::getHumanizedDate($item->invoice->getLastPayment())?></span>
                                            </td>
                                            <td>
                                                <span><?= $item->comission_pay_detail_percent ?> %</span>
                                            </td>
                                            <td class="text-right">
                                                <?= CurrencyComponent::formatMoney($item->comission_pay_detail_amount) ?>
                                            </td>
                                        </tr>
                                        <?php endforeach;?>
                                        <tr>
                                            <td colspan="6" class="text-right"><strong>Total</strong></td>
                                            <td class="text-right"><?= CurrencyComponent::formatMoney($model->comission_pay_value)?></td>
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
