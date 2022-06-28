<?php


use yii\bootstrap4\Html;
 ?>

<div class="card">
	<div class="card-header"> My Cart </div>
	<div class="card-body p-0">
		<?php if(!empty($items)): ?>
 <table class="table table-hover">
 	<tr>
 		<th>Product</th>
 		<th>Image</th>
 		<th>Unit Price</th>
 		<th>Quantity</th>
 		<th>Total Price</th>
 		<th>Action</th>
 	</tr>
 	<tbody>
 		<?php foreach ($items as $item ): ?>
 			<?php print_r($items);die;?>
 	<tr>
 		<td><?php echo $item['name'] ?></td>
 		<td>
 			<img src = "<?php echo Yii::$app->params['frontendUrl'].'/storage'.$item['image'] ?>" style="width :50px" ></img>
 			</td>
 		<td><?php echo $item['price'] ?></td>
 		<td><?php echo $item['quantity'] ?></td>
 		<td><?php echo $item['totalPrice'] ?></td>
 		<td><?php echo Html::a('Delete',['cart/delete','id'=>$item['product_id']],['class'=>'btn btn-outline-danger sm',
 		'data-method'=>'post',
 		'data-confirm'=>'Are you sure you want to remove this product ?'
 		]) ?></td>
 	</tr>
 <?php endforeach; ?>
 	</tbody>
 </table>
 		<div class=" card-body text-right">
 		 <a	href="<?php echo yii\helpers\Url::to(['cart/checkout'])?>" class="btn btn-primary" ?>Checkout</a>
 		 </div>
 		 <php else: >
 		 	<p>Please add some items into cart.</p>
 		<?php endif; ?>
 </div>
 </div>