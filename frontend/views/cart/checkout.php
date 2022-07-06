<?php 

use yii\bootstrap4\Html;
use yii\bootstrap4\ActiveForm;

?>

<?php $form = ActiveForm::begin([
	'action'=>[''],	
]); ?>

<div class="row">
	<div class="col-md-6">
		<div class="card mb-3">
			<div class="card-header"><h5>Account Information</h5></div>	
			<div class="card-body">
				<div class="row">
					<div class="col-md-6">
						<?= $form->field($order, 'firstname')->textInput(['autofocus' => true]) ?>
					</div>
					<div class="col-md-6">
						<?= $form->field($order, 'lastname')->textInput(['autofocus' => true]) ?>                
					</div>
				</div>
				<?= $form->field($order, 'email') ?>
			</div>
		</div>
	</div>

	<div class="col-md-6">
		<div class="card">
			<div class="card-header"><h5>Order Information</h5></div>	
			<div class="card-body">

				<table class="table">
					<tr>
						<td><?php echo $productQuantity ?> Product</td>
					</tr>
					<tr>
						<td>Total Price</td>
						<td class="text-right"><?php echo Yii::$app->formatter->asCurrency($totalPrice) ?></td>
					</tr>
				</table>
				<p class="text-right">
				<button class="btn btn-secondary ">Continue</button>
				</p>
			</div>
		</div>
	</div>
</div>

<div class="row">
	<div class="col-md-12">
	<div class="card">
		<div class="card-header"><h5>Address Information</h5></div>	
		<div class="card-body">

			<?= $form->field($orderAddress, 'city')->textInput(['autofocus' => true]) ?>
			<?= $form->field($orderAddress, 'state')->textInput(['autofocus' => true]) ?>
			<?= $form->field($orderAddress, 'pincode')->textInput(['autofocus' => true]) ?>
			<?= $form->field($orderAddress, 'country')->textInput(['autofocus' => true]) ?>
</div>
		</div>
	</div>
</div>

<?php ActiveForm::end() ?>
