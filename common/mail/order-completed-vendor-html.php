<?php

$orderAddress = $order->orderAddresses;
?>

<div class="row">
	<div class="col">
		<h2>Order #<?php echo $order->id?></h2>
		<h4>Account Information</h4>
		<table class="table">
			<tr>
				<th>Firstname</th>
				<td class="text-right"><?php echo $order->firstname?></td>
			</tr>
			<tr>					
				<th>Lastname</th>
				<td class="text-right"><?php echo $order->lastname?></td>
			</tr>
			<tr>
				<th>Email</th>
				<td class="text-right"><?php echo $order->email?></td>
			</tr>
			<tr>
				<th>Phone No</th>
				<td class="text-right"><?php echo $order->mobile_no?></td>
			</tr>
		</table>

		<h4>Address Information</h4>
		<table class="table">
			<tr>
				<th>Address</th>
				<td class="text-right"><?php echo $orderAddress->address?></td>
			</tr>
			<tr>
				<th>City</th>
				<td class="text-right"><?php echo $orderAddress->city?></td>
			</tr>
			<tr>	
				<th>State</th>	
				<td class="text-right"><?php echo $orderAddress->state?></td>
			</tr>
			<tr>
				<th>Country</th>
				<td class="text-right"><?php echo $orderAddress->country?></td>
			</tr>
			<tr>
				<th>PinCode</th>
				<td class="text-right"><?php echo  $orderAddress->pincode?></td>
			</tr>
		</table>
	</div>

	<div class="col">
			<h4>Product Details</h4>
		<table class="table">	
			<tr>
				<th>Image</th>
				<th>Name</th>
				<th>Quantity</th>
				<th>Total Price</th>
			</tr>
			<tbody>
				<?php foreach($order->orderItems as $item): ?>
					<tr>
						<td><img src='<?php echo $item->product->getImageUrl()?>' style='width: 50px'></td>
						<td><?php echo $item->product_name ?></td>
						<td><?php echo $item->quantity ?></td>
						<td><?php echo Yii::$app->formatter->asCurrency($item->unit_price * $item->quantity) ?></td>						
					</tr>
				</tbody>
			<?php endforeach; ?>
		</table>
		<hr>
		<h4>Order Summary</h4>
		<table class="table">
			<tr>
			<th>Total Item</th>
			<td><?php echo $order->getItemsQuantity() ?></td>
			</tr>
			<tr>
			<th>Total Price</th>
			<td><?php echo Yii::$app->formatter->asCurrency($order->total_price) ?></td>
		</tr>
		</table>
	</div>


</div>