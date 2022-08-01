<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use yii\widgets\Pjax;
use yii\grid\GridView;
use frontend\components\LabelComponent;
use frontend\components\ButtonComponent;
use frontend\components\AccessComponent;
use common\components\CurrencyComponent;
use app\components\BaseController;
use common\models\Customer;
// use kartik\grid\GridView;

$this->title = BaseController::getCustomPageTitle(BaseController::$page_caption);
$toolbar[] = ButtonComponent::getAddButton();

BaseController::$toolbar = $toolbar;

foreach(Yii::$app->session->getAllFlashes() as $key => $message)
    echo '<div class="alert alert-' . $key . '">' . $message . "</div>\n";
?>
<input id="baseUrl" type="hidden" value="<?=Url::base()?>/sales/indent">
<section class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-content">
                <div class="card-body">
                	<div class="table-responsive">
                    <?= GridView::widget([
                    	'id' => 'asd',
                        'dataProvider' => $dataProvider,
                        'filterModel' => $searchModel,
                        'formatter' => ['class' => 'yii\i18n\Formatter','nullDisplay' => ''],
                        'columns' => [
                            ['class' => 'yii\grid\SerialColumn'],
						    // [
          //                   	'attribute' => 'indent_date',
          //                   	'value' => function($model) {
          //                   		return date("d-m-Y", strtotime($model->indent_date));
          //                   	},
          //                   	'filter' => Html::activeTextInput($searchModel, 'indent_date', ['class' => 'form-control', 'id' => 'animate'])
          //                   ],
                            [
								'attribute' => 'product_material_code',
								'format' => 'raw',
								'value' => function($model){
									return $model->product->product_material_code;
								}
							],
							[
								'attribute' => 'product_material_name',
								'format' => 'raw',
								'value' => function($model){
									return $model->product->product_material_name;
								}
							],
							[
								'attribute' => 'product_code',
								'format' => 'raw',
								'value' => function($model){
									return $model->product->product_code;
								}
							],
							[
								'attribute' => 'product_name',
								'format' => 'raw',
								'value' => function($model){
									return $model->product->product_name;
								}
							],
							[
								'attribute' => 'factory_name',
								'format' => 'raw',
								'value' => function($model){
									return $model->product->factory->factory_name;
								}
							],
							[
								'attribute' => 'brand_name',
								'format' => 'raw',
								'value' => function($model){
									return $model->product->brand->brand_name;
								}
							],
							[
								'attribute' => 'category_name',
								'format' => 'raw',
								'value' => function($model){
									return isset($model->product->category->category_name) ? $model->product->category->category_name : '';
								}
							],
                            [
								'attribute' => 'customer_name',
								'format' => 'raw',
								'value' => function($model){
									return $model->indent->customer->customerName;
								},
							],
							
							'qty',
							[
								'attribute' => 'indent_item_is_complete',
								'format' => 'raw',
								'value' => function($model){
									if($model->indent_item_is_complete == 1)
										return Html::checkbox('indent_item_is_complete', '', ['class' => ' is-complete', 'id' => 'indent-item-'.$model->id, 'checked' => true]);
									else
										return Html::checkbox('indent_item_is_complete', '', ['class' => ' is-complete', 'id' => 'indent-item-'.$model->id]);
								},
							],
                            [
								'class' => 'yii\grid\ActionColumn',
								'header'   => 'Actions',
								'template' => '<div style="white-space: nowrap;">{update}{delete}</div>',
								'buttons'  => [
									'update' => function ($url, $model) {
										if (AccessComponent::hasAccess(Yii::$app->controller->module->id, Yii::$app->controller->id, 'update')) {
											$url = Url::to(['update', 'id' => $model->indent->id]);
											return Html::a('<span class="btn btn-outline-warning btn-sm"><i class="la la-pencil"></i></span> ', $url, ['title' => 'Update']);
										}
										return "";
									},
									'delete' => function ($url, $model) {
										if (AccessComponent::hasAccess(Yii::$app->controller->module->id, Yii::$app->controller->id, 'delete')) {
											$url = Url::to(['delete', 'id' => $model->id]);
											return Html::a(
												'<span class="btn btn-outline-danger btn-sm"><i class="la la-trash"></i></span>',
												'#',
												[
													'title' => Yii::t('yii', 'Delete'),
													'aria-label' => Yii::t('yii', 'Delete'),
													'onclick' => "deleteclick('".$url."');"
												]
											);
										}
										return "";
									}
								],
								'headerOptions' => ['style' => 'width:7%'],
							],
						],
					]); ?>
					</div>
                    </div>
                </div>
            </div>
        </div>
    </section>

<script>
$(document).on('click', '.is-complete', function(event){
	if ($(this).is(':checked')) {
        var id = $(this).attr("id").split("-")[2];
	    var url = $('#baseUrl').val();
	    // alert(url);
	    $.ajax({
	      method: "POST",
	      url: url+"/updateiscompletetrue?id="+id,
	    }).done(function( msg ) {
	    
	    });
    }
    else{
    	var id = $(this).attr("id").split("-")[2];
	    var url = $('#baseUrl').val();
	    // alert(id);
	    $.ajax({
	      method: "POST",
	      url: url+"/updateiscompletefalse?id="+id,
	    }).done(function( msg ) {
	    
	    });
    }

});

$(document).ready(function(){

	
});

</script>
