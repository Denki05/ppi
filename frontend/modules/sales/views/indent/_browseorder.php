<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use yii\bootstrap\Modal;
use yii\grid\GridView;
use yii\widgets\Pjax;
use backend\components\LabelComponent;
use common\components\CurrencyComponent;
use backend\components\AccessComponent;
use common\models\Customer;
?>
<div id="modal-order" class="modal fade text-left" tabindex="-1" role="dialog" aria-labelledby="myModalLabel16" aria-hidden="true">
	<div class="modal-dialog modal-xl" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h4 class="modal-title" id="myModalLabel16">Cari Order</h4>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<?php Pjax::begin(['id' => 'pjax-order-grid', 'enablePushState' => false, 'timeout' => 1000000]); ?>
					<?= GridView::widget([
						'id' => 'order-grid',
	                    'dataProvider' => $dataProvider,
	                    'filterModel' => $searchModel,
	                    'formatter' => ['class' => 'yii\i18n\Formatter','nullDisplay' => ''],
	                    'columns' => [
	                        ['class' => 'yii\grid\SerialColumn'],
						    'order_code',
	                        [
	                        	'attribute' => 'order_date',
	                        	'value' => function($model) {
	                        		return date("d-m-Y", strtotime($model->order_date));
	                        	},
	                        ],
							[
								'attribute' => 'product_name',
								'value' => function($model) {
									return $model->product->product_name;
								},
								'filter' => Html::activeTextInput($searchModel, 'product_name', ['class' => 'form-control']).Html::activeHiddenInput($searchModel, 'customer_id').Html::activeHiddenInput($searchModel, 'chosen_order')
							],
	                        'order_customer_name',
	                        [
								'attribute' => 'product_name',
								'value' => function($model) {
									return $model->product->product_name;
								}
							],
	                        'order_customer_phone',
	                        [
								'class' => 'yii\grid\ActionColumn',
								'header'   => 'Actions',
								'template' => '<div style="white-space: nowrap;">{add}</div>',
								'buttons'  => [
									'add' => function ($url, $model) {
										return '<button data-dismiss="modal" data-rowindex="0" type="button" class="btn btn-outline-primary btn-add-order btn-sm glyphicon glyphicon-plus" data-orderid="'.$model->id.'" data-ordercode="'.$model->order_code.'" data-productname="'.$model->product->product_name.'" data-orderdate="'.date("d-m-Y", strtotime($model->order_date)).'" data-orderqty="'.$model->order_qty.'" data-orderprice="'.$model->order_price.'"></button>';
									},
								],
							],
						],
					]); ?>
				<?php Pjax::end(); ?>
			</div>
		</div>
	</div>
</div>