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
use common\models\PurchasePayment;
use common\models\PurchasePaymentDetail;


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
				<h4 class="card-title">Pembayaran PO</h4>
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
                    	<div class="col-lg-12">
    	                	<div class="row mb-1">
    		            		<div class="col-lg-4"><b><?=$model->getAttributeLabel('purchase_payment_date')?></b></div>
    		            		<div class="col-lg-1">:</div>
    		            		<div class="col-lg-5"><?=$model->purchase_payment_date ?></div>
    	                	</div>	
    	                	<div class="row mb-1">
    		            		<div class="col-lg-4"><b><?=$model->getAttributeLabel('purchase_payment_code')?></b></div>
    		            		<div class="col-lg-1">:</div>
    		            		<div class="col-lg-5"><?= $model->purchase_payment_code ?></div>
    	                	</div>	
    	                	<div class="row mb-1">
    		            		<div class="col-lg-4"><b><?=$model->getAttributeLabel('supplier_id')?></b></div>
    		            		<div class="col-lg-1">:</div>
    		            		<div class="col-lg-5"><?=$model->supplier->supplier_name ?></div>
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
                                            <th style="width: 10%">No</th>
                                            <th style="width: 50%"><?= (new PurchasePaymentDetail)->getAttributeLabel('purchase_payment_detail_method')?></th>
                                            <th style="width: 40%;"><?= (new PurchasePaymentDetail)->getAttributeLabel('purchase_payment_detail_amount')?></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php 
                                            $n = 1;
                                            foreach($model->purchasePaymentDetails as $item):
                                        ?>
                                        <tr class="row-item">
                                            <td>
                                                <span><?=  $n++ ?></span>
                                            </td>
                                            <td>
                                                <span><?= $item->getPayMethodLabel($item->purchase_payment_detail_method) ?></span><br>
                                                <span><?= $item->getMethodDetail($item->purchase_payment_detail_method) ?></span>
                                            </td>
                                            <td>
                                                <?= CurrencyComponent::formatMoney($item->purchase_payment_detail_amount,0,',','.', Product::CURRENCY_DOLAR) ?>
                                            </td>
                                        </tr>
                                        <?php endforeach;?>
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
