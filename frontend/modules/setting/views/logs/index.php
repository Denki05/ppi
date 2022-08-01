<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use yii\widgets\Pjax;
//use yii\grid\GridView;
use frontend\components\LabelComponent;
use frontend\components\AccessComponent;
use app\components\BaseController;
use common\components\CurrencyComponent;
use common\models\Event;
use common\models\User;
use kartik\grid\GridView;

$this->title = BaseController::getCustomPageTitle(BaseController::$page_caption);

BaseController::$toolbar = [];

foreach(Yii::$app->session->getAllFlashes() as $key => $message)
	echo '<div class="alert alert-' . $key . '">' . $message . "</div>\n";
?>
<div class="row">
	<div class="col-lg-12">
		<div class="ibox">
			<div class="ibox-content">
				<div class="table-responsive">
					<?= GridView::widget([
						'id' => 'logs-grid',
						'dataProvider' => $dataProvider,
						'filterModel' => $searchModel,
						'responsiveWrap' => true,
						'condensed' => true,
						'pjax' => true,
						'columns' => [
							['class' => 'yii\grid\SerialColumn'],
							[
								'attribute' => 'logs_date',
								'value' => function ($model, $key, $index, $column){
									return date("d-m-Y H:i:s", strtotime($model->logs_date));
								},
								'filter' => Html::activeTextInput($searchModel, 'start_date', ['class' => 'form-control datepicker'])." hingga ".Html::activeTextInput($searchModel, 'end_date', ['class' => 'form-control datepicker'])
							],
							[
								'attribute' => 'user_id',
								'value' => function ($model, $key, $index, $column){
									return isset($model->user) ? $model->user->username : "<Unknown User>";
								},
								'filter' => Html::activeDropDownList($searchModel, 'user_id', ArrayHelper::map(User::find()->andWhere('status=:status', [':status' => '10'])->all(), 'id', 'username'), ['class' => 'form-control select2-select'])
							],
							[
								'attribute' => 'logs_type',
								'value' => function ($model, $key, $index, $column){
									return $model->getLogsTypeLabel($model->logs_type);
								},
								'filter' => $searchModel->getLogsTypeLabel()
							],
							[
								'attribute' => 'logs_category',
								'value' => function ($model, $key, $index, $column){
									return !empty($model->logs_category) ? $model->getCategoryLabel($model->logs_category) : "";
								},
								'filter' => Html::activeDropDownList($searchModel, 'logs_category', $searchModel->getCategoryLabel(), ['class' => 'form-control', 'prompt' => ''])
							],
							[
								'attribute' => 'logs_desc',
								'format' => 'raw',
							],
							[
								'class' => 'yii\grid\ActionColumn',
								'header'   => 'Actions',
								'template' => '<div style="white-space: nowrap;">{delete}</div>',
								'buttons'  => [
									'delete' => function ($url, $model) {
										$url = Url::to(['delete', 'id' => $model->logs_id]);
										if (AccessComponent::hasAccess(Yii::$app->controller->module->id, Yii::$app->controller->id, 'delete')) {
											return Html::a('<span class="btn btn-danger dim btn-xs glyphicon glyphicon-'.LabelComponent::ICON_DELETE.'"></span> ', $url, [
												'title' => 'Delete',
												'data-method' => 'post',
												'data-confirm' => Yii::t('yii', 'Are you sure you want to delete this item?'),
											]);
										}
										return "";
									}
								],
							],
						],
					]); ?>
				</div>
			</div>
		</div>
	</div>
</div>
<script type="text/javascript">
$(document).on('pjax:complete', function(e) {
	$('.datepicker').datepicker({
		todayBtn: "linked",
		keyboardNavigation: false,
		forceParse: false,
		calendarWeeks: true,
		autoclose: true,
		format: 'dd-mm-yyyy',
	});
});
</script>