<?php 

use yii\bootstrap4\ActiveForm;
use yii\widgets\Pjax;

?>

<?php if(isset($success) && $success): ?>
	<div class="alert alert-success">
	Your Account was successfully updated
	</div>
<?php endif ?>

<?php $form = ActiveForm::begin([
	'action'=>['profile/update-account'],
	'options'=>[
		'data-pjax'=>1
	]
]); ?>

<?= $form->field($user, 'firstname')->textInput(['autofocus' => true]) ?>

<?= $form->field($user, 'lastname')->textInput(['autofocus' => true]) ?>                

<?= $form->field($user, 'username')->textInput(['autofocus' => true]) ?>

<?= $form->field($user, 'email') ?>

<?= $form->field($user, 'mobile_no')->textInput(['autofocus' => true]) ?>
<div class="row">
	<div class="col-md-6">
		<?= $form->field($user, 'password')->passwordInput() ?>
	</div>
	<div class="col-md-6">
		<?= $form->field($user, 'password_repeat')->passwordInput() ?>
	</div>
</div>
<button class="btn btn-primary" type="submit">
	Update
</button> 
<?php ActiveForm::end() ?>
