<?php


use yii;
use yii\bootstrap4\Html;

?>

<div class="row">
	<div class="col">
		<h4>Account Information</h4>
		<dl>
			<dt>Firstname</dt>
			<dd><?php $order->firstname?></dd>
			<dt>Lastname</dt>
			<dd><?php $order->lastname?></dd>
			<dt>Email</dt>
			<dd><?php $order->email?></dd>
			<dt>Phone No</dt>
			<dd><?php $order->mobile_no?></dd>
		</dl>

		<h4>Address Information</h4>
		<dl>
			<dt>Address</dt>
			<dd><?php $order->address?></dd>
			<dt>City</dt>
			<dd><?php $order->city?></dd>
			<dt>State</dt>
			<dd><?php $order->state?></dd>
			<dt>Country</dt>
			<dd><?php $order->country?></dd>
			<dt>PinCode</dt>
			<dd><?php $order->pincode?></dd>
		</dl>
	</div>

	<div class="col">
		<table class="table table-sm">
			<thead>
			<tr>
				<th>Name</th>
				<th>Quantity</th>
				<th>Price</th>
			</tr>
			</thead>
			<tbody>
				<?php foreach($order->orderItems as $item): ?>
					<tr>
						<td><?php echo $order->product_name ?></td>
						<td><?php echo $item->quantity ?></td>
						<td><?php echo $item->price * $item->quantity ?></td>						
					</tr>
			</tbody>
		</table>
	</div>
	
</div>