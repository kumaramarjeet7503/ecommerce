<?php 

use yii\bootstrap4\Html;
use yii\bootstrap4\ActiveForm;
use yii\helpers\Url;
use common\models\Product;
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
				<th>Image</th>
				<th>Name</th>
				<th>Quantity</th>
				<th>Total Price</th>
			</tr>
			<tbody>
				<?php foreach($cartItems as $item): ?>
					<tr>
						<td><img src='<?php echo Product::formatImageUrl($item['image']) ?>' style='width: 50px'></td>
						<td><?php echo $item['name'] ?></td>
						<td><?php echo $item['quantity'] ?></td>
						<td><?php echo Yii::$app->formatter->asCurrency($item['price'] * $item['quantity']) ?></td>						
					</tr>
				</tbody>
			<?php endforeach; ?>
		</table>

				<p class="text-right mt-3">
					<button class="btn btn-secondary">Checkout</button>
				</p>
			</div>
		</div>
	</div>
</div>

<?php ActiveForm::end() ?>

