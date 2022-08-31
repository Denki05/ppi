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
				<h4 class="card-title">Barang</h4>
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
	            		<div class="col-lg-4"><b><?=$model->getAttributeLabel('product_material_code')?></b></div>
	            		<div class="col-lg-1">:</div>
	            		<div class="col-lg-5"><?=isset($model->product_material_code) ? $model->product_material_code : '' ?></div>
                	</div>	
                	<div class="row mb-1">
	            		<div class="col-lg-4"><b><?=$model->getAttributeLabel('product_material_name')?></b></div>
	            		<div class="col-lg-1">:</div>
	            		<div class="col-lg-5"><?=isset($model->product_material_name) ? $model->product_material_name : '' ?></div>
                	</div>	
                	<div class="row mb-1">
	            		<div class="col-lg-4"><b><?=$model->getAttributeLabel('product_code')?></b></div>
	            		<div class="col-lg-1">:</div>
	            		<div class="col-lg-5"><?= $model->product_code?></div>
                	</div>
                	<div class="row mb-1">
	            		<div class="col-lg-4"><b><?=$model->getAttributeLabel('product_name')?></b></div>
	            		<div class="col-lg-1">:</div>
	            		<div class="col-lg-5"><?=$model->product_name ?></div>
                	</div>
                	<div class="row mb-1">
	            		<div class="col-lg-4"><b><?=$model->getAttributeLabel('factory_id')?></b></div>
	            		<div class="col-lg-1">:</div>
	            		<div class="col-lg-5"><?=$model->factory->factory_name?></div>
                	</div>
                	<div class="row mb-1">
	            		<div class="col-lg-4"><b><?=$model->getAttributeLabel('brand_id')?></b></div>
	            		<div class="col-lg-1">:</div>
	            		<div class="col-lg-5"><?=$model->brand->brand_name?></div>
                	</div>
                	<div class="row mb-1">
	            		<div class="col-lg-4"><b><?=$model->getAttributeLabel('category_id')?></b></div>
	            		<div class="col-lg-1">:</div>
	            		<div class="col-lg-5"><?=$model->category->category_name?></div>
                	</div>
                	<div class="row mb-1">
	            		<div class="col-lg-4"><b><?=$model->getAttributeLabel('original_brand_id')?></b></div>
	            		<div class="col-lg-1">:</div>
	            		<div class="col-lg-5"><?=$model->originalBrand->brand_name?></div>
                	</div>
                	<div class="row mb-1">
	            		<div class="col-lg-4"><b><?=$model->getAttributeLabel('searah_id')?></b></div>
	            		<div class="col-lg-1">:</div>
	            		<div class="col-lg-5"><?=$model->searah->searah_name?></div>
                	</div>
                </div>
            </div>
        </div>
    </div>
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
	            		<div class="col-lg-4"><b><?=$model->getAttributeLabel('product_is_new')?></b></div>
	            		<div class="col-lg-1">:</div>
	            		<div class="col-lg-5"><?=$model->product_is_new === 1 ? 'Ya' : 'Tidak' ?></div>
                	</div>
                	<div class="row mb-1">
	            		<div class="col-lg-4"><b><?=$model->getAttributeLabel('product_gender')?></b></div>
	            		<div class="col-lg-1">:</div>
	            		<div class="col-lg-5"><?=$model->product_gender === 'm' ? 'Laki - Laki' : 'Perempuan'?></div>
                	</div>
                	<div class="row mb-1">
	            		<div class="col-lg-4"><b><?=$model->getAttributeLabel('product_cost_price')?></b></div>
	            		<div class="col-lg-1">:</div>
	            		<div class="col-lg-5"><?=CurrencyComponent::formatMoney($model->product_cost_price,0,',','.', Product::CURRENCY_DOLAR)?></div>
                	</div>
                	<div class="row mb-1">
	            		<div class="col-lg-4"><b><?=$model->getAttributeLabel('product_sell_price')?></b></div>
	            		<div class="col-lg-1">:</div>
	            		<div class="col-lg-5"><?=CurrencyComponent::formatMoney($model->product_sell_price,0,',','.', Product::CURRENCY_DOLAR)?></div>
                	</div>
                	<div class="row mb-1">
	            		<div class="col-lg-4"><b><?=$model->getAttributeLabel('product_status')?></b></div>
	            		<div class="col-lg-1">:</div>
	            		<div class="col-lg-5"><?=$model->product_status?></div>
                	</div>
					<div class="row mb-1">
	            		<div class="col-lg-4"><b><?=$model->getAttributeLabel('product_live')?></b></div>
	            		<div class="col-lg-1">:</div>
	            		<div class="col-lg-5"><?=$model->product_live?></div>
                	</div>	
                	<div class="row mb-1">
	            		<div class="col-lg-4"><b><?=$model->getAttributeLabel('product_web_image')?></b></div>
	            		<div class="col-lg-1">:</div>
	            		<div class="col-lg-5"><?=isset($model->product_web_image) ? $model->product_web_image : ''; ?></div>
                	</div>	
                </div>
            </div>
        </div>
    </div>
</section>