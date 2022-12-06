<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use app\components\BaseController;
use frontend\components\LabelComponent;
use frontend\components\AccessComponent;
use frontend\components\ButtonComponent;
use common\components\DateComponent;
use common\components\CurrencyComponent;
use common\models\Product;


$this->title = BaseController::getCustomPageTitle(BaseController::$page_caption);

$toolbar[] = ButtonComponent::getBackButton();
	
BaseController::$toolbar = $toolbar;
	
foreach(Yii::$app->session->getAllFlashes() as $key => $message)
	echo '<div class="alert alert-' . $key . '">' . $message . "</div>\n";

?>
<section class="row">
    <div class="col-lg-6">
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
                <div class="card-body">
                	<div class="row mb-1">
	            		<div class="col-lg-4"><b><?=$model->getAttributeLabel('customer_type')?></b></div>
	            		<div class="col-lg-1">:</div>
	            		<div class="col-lg-5"><?=isset($model->customer_type) ? $model->customer_type : ''?></div>
                	</div>	
                	<div class="row mb-1">
	            		<div class="col-lg-4"><b><?=$model->getAttributeLabel('customer_has_tempo')?></b></div>
	            		<div class="col-lg-1">:</div>
	            		<div class="col-lg-5"><?=$model->customer_has_tempo === 1 ? 'Ya' : 'Tidak'?></div>
                	</div>	
                	<div class="row mb-1">
	            		<div class="col-lg-4"><b><?=$model->getAttributeLabel('customer_tempo_limit')?></b></div>
	            		<div class="col-lg-1">:</div>
	            		<div class="col-lg-5"><?= isset($model->customer_tempo_limit) ? $model->customer_tempo_limit : ''?></div>
                	</div>
                	<div class="row mb-1">
	            		<div class="col-lg-4"><b><?=$model->getAttributeLabel('customer_credit_limit')?></b></div>
	            		<div class="col-lg-1">:</div>
	            		<div class="col-lg-5"><?=isset($model->customer_credit_limit) ? CurrencyComponent::formatMoney($model->customer_credit_limit) : ''?></div>
                	</div>
                	<div class="row mb-1">
	            		<div class="col-lg-4"><b><?=$model->getAttributeLabel('customer_identity_card_number')?></b></div>
	            		<div class="col-lg-1">:</div>
	            		<div class="col-lg-5"><?=isset($model->customer_identity_card_number) ? $model->customer_identity_card_number : ""?></div>
                	</div>
                	<div class="row mb-1">
	            		<div class="col-lg-4"><b><?=$model->getAttributeLabel('customer_identity_card_image')?></b></div>
	            		<div class="col-lg-1">:</div>
	            		<div class="col-lg-5">
	            			<?php if(isset($model->customer_identity_card_image)): ?>
	            				<img src="<?=Url::base(true)?>/uploads/customers/<?=$model->customer_identity_card_image?>" alt="" style="width: 50%;"/>
	            			<?php endif; ?>
	            		</div>
                	</div>
                	<div class="row mb-1">
	            		<div class="col-lg-4"><b><?=$model->getAttributeLabel('customer_npwp')?></b></div>
	            		<div class="col-lg-1">:</div>
	            		<div class="col-lg-5"><?=isset($model->customer_npwp) ? $model->customer_npwp : ""?></div>
                	</div>
                	<div class="row mb-1">
	            		<div class="col-lg-4"><b><?=$model->getAttributeLabel('customer_npwp_image')?></b></div>
	            		<div class="col-lg-1">:</div>
	            		<div class="col-lg-5">
	            			<?php if(isset($model->customer_npwp_image)): ?>
	            				<img src="<?=Url::base(true)?>/uploads/customers/<?=$model->customer_npwp_image?>" alt="" style="width: 50%;"/>
	            			<?php endif; ?>
	            		</div>
                	</div>
                	<div class="row mb-1">
	            		<div class="col-lg-4"><b><?=$model->getAttributeLabel('customer_has_ppn')?></b></div>
	            		<div class="col-lg-1">:</div>
	            		<div class="col-lg-5"><?=$model->customer_has_ppn === 1 ? 'Ya' : 'Tidak'?></div>
                	</div>
					<div class="row mb-1">
	            		<div class="col-lg-4"><b><?=$model->getAttributeLabel('customer_free_shipping')?></b></div>
	            		<div class="col-lg-1">:</div>
	            		<div class="col-lg-5"><?=$model->customer_free_shipping === 1 ? 'Ya' : 'Tidak'?></div>
                	</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-6">
        <div class="card">
        	<div class="card-header">
				<h4 class="card-title">Toko Customer</h4>
				<a class="heading-elements-toggle"><i class="la la-ellipsis-v font-medium-3"></i></a>
				<div class="heading-elements">
					<ul class="list-inline mb-0">
						<li><a data-action="collapse"><i class="ft-minus"></i></a></li>
					</ul>
				</div>
			</div>
            <div class="card-content">
                <div class="card-body">
                	<div class="row mb-1">
	            		<div class="col-lg-4"><b><?=$model->getAttributeLabel('customer_store_code')?></b></div>
	            		<div class="col-lg-1">:</div>
	            		<div class="col-lg-5"><?=$model->customer_store_code?></div>
                	</div>	
                	<div class="row mb-1">
	            		<div class="col-lg-4"><b><?=$model->getAttributeLabel('customer_store_name')?></b></div>
	            		<div class="col-lg-1">:</div>
	            		<div class="col-lg-5"><?=$model->customer_store_name?></div>
                	</div>
                	<div class="row mb-1">
	            		<div class="col-lg-4"><b><?=$model->getAttributeLabel('customer_owner_name')?></b></div>
	            		<div class="col-lg-1">:</div>
	            		<div class="col-lg-5"><?=isset($model->customer_owner_name) ? $model->customer_owner_name : ''?></div>
                	</div>	
                	<div class="row mb-1">
	            		<div class="col-lg-4"><b><?=$model->getAttributeLabel('customer_zone')?></b></div>
	            		<div class="col-lg-1">:</div>
	            		<div class="col-lg-5"><?=isset($model->customer_zone) ? $model->customer_zone : ''?></div>
                	</div>
                	<div class="row mb-1">
	            		<div class="col-lg-4"><b><?=$model->getAttributeLabel('customer_province')?></b></div>
	            		<div class="col-lg-1">:</div>
	            		<div class="col-lg-5"><?=isset($model->customer_province) ? $model->customer_province : ''?></div>
                	</div>
                	<div class="row mb-1">
	            		<div class="col-lg-4"><b><?=$model->getAttributeLabel('customer_city')?></b></div>
	            		<div class="col-lg-1">:</div>
	            		<div class="col-lg-5"><?=isset($model->customer_city) ? $model->customer_city : ''?></div>
                	</div>
                	<div class="row mb-1">
	            		<div class="col-lg-4"><b><?=$model->getAttributeLabel('customer_store_postal_code')?></b></div>
	            		<div class="col-lg-1">:</div>
	            		<div class="col-lg-5"><?=$model->customer_store_postal_code?></div>
                	</div>
                	<div class="row mb-1">
	            		<div class="col-lg-4"><b><?=$model->getAttributeLabel('customer_store_address')?></b></div>
	            		<div class="col-lg-1">:</div>
	            		<div class="col-lg-5"><?=$model->customer_store_address?></div>
                	</div>
                	<div class="row mb-1">
	            		<div class="col-lg-4"><b><?=$model->getAttributeLabel('customer_bank_name')?></b></div>
	            		<div class="col-lg-1">:</div>
	            		<div class="col-lg-5"><?=isset($model->customer_bank_name) ? $model->customer_bank_name : ''?></div>
                	</div>
                	<div class="row mb-1">
	            		<div class="col-lg-4"><b><?=$model->getAttributeLabel('customer_bank_acc_name')?></b></div>
	            		<div class="col-lg-1">:</div>
	            		<div class="col-lg-5"><?=isset($model->customer_bank_acc_name) ? $model->customer_bank_acc_name : ''?></div>
                	</div>
                	<div class="row mb-1">
	            		<div class="col-lg-4"><b><?=$model->getAttributeLabel('customer_bank_acc_number')?></b></div>
	            		<div class="col-lg-1">:</div>
	            		<div class="col-lg-5"><?=isset($model->customer_bank_acc_number) ? $model->customer_bank_acc_number : ''?></div>
                	</div>
                	<div class="row mb-1">
	            		<div class="col-lg-4"><b><?=$model->getAttributeLabel('customer_birthday')?></b></div>
	            		<div class="col-lg-1">:</div>
	            		<div class="col-lg-5"><?=isset($model->customer_birthday) ? $model->customer_birthday : ''?></div>
                	</div>
                	<div class="row mb-1">
	            		<div class="col-lg-4"><b><?=$model->getAttributeLabel('customer_phone1')?></b></div>
	            		<div class="col-lg-1">:</div>
	            		<div class="col-lg-5"><?=isset($model->customer_phone1) ? $model->customer_phone1 : ''?></div>
                	</div>
                	<div class="row mb-1">
	            		<div class="col-lg-4"><b><?=$model->getAttributeLabel('customer_phone2')?></b></div>
	            		<div class="col-lg-1">:</div>
	            		<div class="col-lg-5"><?=isset($model->customer_phone2) ? $model->customer_phone2 : ''?></div>
                	</div>
                	<div class="row mb-1">
	            		<div class="col-lg-4"><b><?=$model->getAttributeLabel('customer_note')?></b></div>
	            		<div class="col-lg-1">:</div>
	            		<div class="col-lg-5"><?=isset($model->customer_note) ? $model->customer_note : ''?></div>
                	</div>
                </div>
            </div>
        </div>
    </div>
</section>