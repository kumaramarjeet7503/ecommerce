<?php 

use yii\bootstrap4\Html;
use yii\bootstrap4\ActiveForm;
use yii\widgets\Pjax;

?>

		<?php if(isset($success) && $success): ?>
		<div class="alert alert-success">
			Your Address was successfully updated.
		</div>
		<?php endif ?>

		<?php $form = ActiveForm::begin([
			'action'=>['profile/update-address'],
			'options' => [
				'data-pjax'=>1 
			]
		]); ?>
				<?= $form->field($userAddress, 'address')->textInput(['autofocus' => true]) ?>
				<?= $form->field($userAddress, 'city')->textInput(['autofocus' => true]) ?>
				<?= $form->field($userAddress, 'state')->textInput(['autofocus' => true]) ?>
				<?= $form->field($userAddress, 'pincode')->textInput(['autofocus' => true]) ?>
				<?= $form->field($userAddress, 'country')->textInput(['autofocus' => true]) ?>
				<button class="btn btn-primary" type="submit">
					Update
				</button> 
		<?php ActiveForm::end(); ?>
