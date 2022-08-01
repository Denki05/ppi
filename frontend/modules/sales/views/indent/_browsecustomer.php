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
<div id="modal-customer" class="modal fade text-left" tabindex="-1" role="dialog" aria-labelledby="myModalLabel16" aria-hidden="true">
	<div class="modal-dialog modal-xl" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h4 class="modal-title" id="myModalLabel16">Cari Customer</h4>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<?php Pjax::begin(['id' => 'pjax-customer-grid', 'enablePushState' => false, 'timeout' => 1000000]); ?>
					<?=GridView::widget([
						'id' => 'customer-grid',
						'dataProvider' => $dataProvider,
						'filterModel' => $searchModel,
						'columns' => [
							['class' => 'yii\grid\SerialColumn'],
							'customer_name',
							[
								'attribute' => 'customer_address',
								'format' => 'raw',
							],
							'customer_phone1',
							'customer_phone2',
							[
								'class' => 'yii\grid\ActionColumn',
								'header'   => 'Actions',
								'template' => '<div style="white-space: nowrap;">{add}</div>',
								'buttons'  => [
									'add' => function ($url, $model) {
										return '<button data-dismiss="modal" type="button" class="btn btn-outline-primary btn-add-customer btn-sm glyphicon glyphicon-plus" data-customerid="'.$model->id.'" data-customername="'.$model->customer_name.'"></button>';
									},
								],
							],
						],
					]);?>
				<?php Pjax::end(); ?>
			</div>
		</div>
	</div>
</div>