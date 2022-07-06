<?php


use yii\bootstrap4\Html;
use yii\helpers\Url;

 ?>

<div class="card">
	<div class="card-header"> My Cart </div>
	<div class="card-body p-0">
		<?php if(!empty($items)): ?>
 <table class="table table-hover ">
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

 	<tr class="table-light" data-id= "<?php echo $item['id']?>" data-url = "<?php echo Url::to(['/cart/change-quantity']) ?>" >
 		<td><?php echo $item['name'] ?></td>
 		<td>
 			<img src = "<?php echo Yii::$app->params['frontendUrl'].'/storage'.$item['image'] ?>" style="width :50px" ></img>
 			</td>
 		<td><?php echo $item['price'] ?></td>
 		<td ><input type="number" min="1" class="form-control item-quantity" style="width: 60px" value="<?php echo $item['quantity'] ?>"></input></td>
 		<td><?php echo $item['totalPrice'] ?></td>
 		
 		<td><?php echo Html::a('Delete',['cart/delete','id'=>$item['id']],['class'=>'btn btn-outline-danger sm','data-method'=>'post','data-confirm'=>'Are you sure you want to remove this product ?']) ?></td>
 		
 	</tr>
 <?php endforeach; ?>
 	</tbody>
 </table>
 		<div class=" card-body text-right">
 		 <a	href="<?php echo yii\helpers\Url::to(['cart/checkout'])?>" class="btn btn-primary" ?>Checkout</a>
 		 </div>
 		 <?php else: ?>
 		 	<p class="text-muted text-center p-5">Please add some items into cart.</p>
 		<?php endif; ?>
 </div>
 </div>