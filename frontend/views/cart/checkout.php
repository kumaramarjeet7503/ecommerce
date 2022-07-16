<?php 

use yii\bootstrap4\Html;
use yii\bootstrap4\ActiveForm;
use yii\helpers\Url;
?>





		<?php $form = ActiveForm::begin([
			'id'=>'checkout-form',	
		]); ?>

<div class="row">
	<div class="col">

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
				<?= $form->field($order, 'mobile_no')->textInput(['autofocus' => true]) ?> 
			</div>
		</div>

		<div class="card">
			<div class="card-header"><h5>Address Information</h5></div>	
			<div class="card-body">

				<?= $form->field($orderAddress, 'address')->textInput(['autofocus' => true]) ?>
				<?= $form->field($orderAddress, 'city')->textInput(['autofocus' => true]) ?>
				<?= $form->field($orderAddress, 'state')->textInput(['autofocus' => true]) ?>
				<?= $form->field($orderAddress, 'pincode')->textInput(['autofocus' => true]) ?>
				<?= $form->field($orderAddress, 'country')->textInput(['autofocus' => true]) ?>
			</div>
		</div>
	</div>


	<div class="col">
		
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

				<p class="text-right mt-3">
					<button class="btn btn-secondary">Checkout</button>
				</p>
			</div>
		</div>
	</div>
</div>

<?php ActiveForm::end() ?>

