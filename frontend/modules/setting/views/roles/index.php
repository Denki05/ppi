<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\GridView;
use frontend\components\LabelComponent;
use yii\widgets\Pjax;
use frontend\components\AccessComponent;
use app\components\BaseController;
use frontend\components\ButtonComponent;

$this->title = BaseController::getCustomPageTitle(BaseController::$page_caption);

$toolbar[] = ButtonComponent::getAddButton();
BaseController::$toolbar = $toolbar;
	
foreach(Yii::$app->session->getAllFlashes() as $key => $message)
	echo '<div class="alert alert-' . $key . '">' . $message . "</div>\n";
?>
<section class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-content">
                <div class="card-body">
					<div class="table-responsive">
						<?php Pjax::begin();?>
							<?= GridView::widget([
								'dataProvider' => $dataProvider,
								'filterModel' => $searchModel,
								'summary' => LabelComponent::GRID_SUMMARY,
								'columns' => [
									'name',
									'description',
									[
										'class' => 'yii\grid\ActionColumn',
										'header'   => 'Actions',
										'template' => '<div style="white-space: nowrap">{update}{delete}</div>',
										'buttons'  => [
											'update' => function ($url, $model) {
												if (AccessComponent::hasAccess(Yii::$app->controller->module->id, Yii::$app->controller->id, 'update')) {
													//$url = Url::to(['update', 'id' => $model->id]);
													return Html::a('<span class="btn btn-outline-warning btn-sm"><i class="la la-pencil"></i></span> ', $url, ['title' => 'Update']);
												}
												return "";
											},
											'delete' => function ($url, $model) {
												if (AccessComponent::hasAccess(Yii::$app->controller->module->id, Yii::$app->controller->id, 'delete')) {
													//$url = Url::to(['delete', 'id' => $model->id]);
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
						<?php Pjax::end();?>
					</div>
				</div>
			</div>
		</div>
	</div>
</section>