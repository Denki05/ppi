
<?php
	$toolbar[] = CHtml::link('<i class="iconfa-reply"></i> ' . Yii::t('admin', 'Kembali'), array('index'), array('class' => 'btn btn-small'));
	$this->toolbar = $toolbar;
	
	$this->widget('bootstrap.widgets.TbAlert',
		array(
			'block'     => TRUE,
			'fade'      => TRUE,
			'closeText' => '&times;',
			'alerts'    => array(
				'success' => array('block' => TRUE, 'fade' => TRUE, 'closeText' => '&times;'),
				'info'    => array('block' => TRUE, 'fade' => TRUE, 'closeText' => '&times;'),
				'warning' => array('block' => TRUE, 'fade' => TRUE, 'closeText' => '&times;'),
				'error'   => array('block' => TRUE, 'fade' => TRUE, 'closeText' => '&times;'),
			),
		)
	);
?>
<div class="form row-fluid">
	<div class="<?php echo $model->isNewRecord ? '' : 'span12';?>">
		<?php $form=$this->beginWidget('bootstrap.widgets.TbActiveForm', array('id'=>'application_form', 'enableAjaxValidation'=>false, 'htmlOptions' => array('enctype' => 'multipart/form-data'))); ?>

		<div class="help-block ptb10"><?php echo Yii::t('admin', 'Kolom dengan tanda <span class="required">*</span> harus diisi.'); ?></div>

		<?php echo $form->errorSummary($model, Lang::getErrorMessage()); ?>

		<table border="0" class="table table-condensed table-striped td-middle" style="">
			<tr>
				<td width="15%"><?php echo $form->labelEx($model,'area_name'); ?></td>
				<td>
					<?php echo $form->textField($model,'area_name', array('class' => 'span3')); ?>
					<?php echo $form->error($model,'area_name'); ?>
				</td>
			</tr>
			<tr>
				<td colspan="2"><input type="submit" name="btn-submit" value="Save" class="submit" /></td>
			</tr>
		</table>	
		<?php $this->endWidget(); ?>
	</div>
</div><!-- form -->
