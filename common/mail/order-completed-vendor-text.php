<?php

$orderAddress = $order->orderAddresses;
?>


		Order #	<?php echo $order->id?>
		Account Information
		
			
			Firstname <?php echo $order->firstname?>
			
								
			Lastname  <?php echo $order->lastname?>
			
			
			Email	<?php echo $order->email?>
		
			Phone No	<?php echo $order->mobile_no?>
	
		Address Information
		
				Address	 <?php echo $orderAddress->address?>
			
				City	<?php echo $orderAddress->city?>
			
				State	<?php echo $orderAddress->state?>
			
				Country	<?php echo $orderAddress->country?>
			
				PinCode	<?php echo  $orderAddress->pincode?>
		

	
			Product Details
						
				Image
				Name
				Quantity
				Total Price
			

				<?php foreach($order->orderItems as $item): ?>
		
						<?php echo $item->product_name ?>
						<?php echo $item->quantity ?>
						<?php echo Yii::$app->formatter->asCurrency($item->unit_price * $item->quantity) ?>			
			<?php endforeach; ?>

		Order Summary
		
			
			Total Item <?php echo $order->getItemsQuantity() ?>
			
			Total Price <?php echo Yii::$app->formatter->asCurrency($order->total_price) ?>
		
