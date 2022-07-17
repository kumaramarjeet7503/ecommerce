<?php



use yii\bootstrap4\Html;
use yii\helpers\Url;

$orderAddress = $order->orderAddresses;

?>

<script src="https://www.paypal.com/sdk/js?client-id=<?php echo param('paypalClientId')?>&currency=USD"></script>

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
		<div id="paypal-button-container"></div>
	</div>


</div>


<script>
	paypal.Buttons({
        // Sets up the transaction when a payment button is clicked
        createOrder: (data, actions) => {
        	return actions.order.create({
        		purchase_units: [{
        			amount: {
        				value: <?php echo $order->total_price?> 
        			}
        		}]
        	});
        },

        onApprove: (data, actions) => {
        	return actions.order.capture().then(function(details) {
            
            // console.log('Capture result', orderData, JSON.stringify(orderData, null, 2));
            const $form = $('#checkout-form');
            var dataAjax = $form.serializeArray();
            // console.log("data",dataAjax);
            dataAjax.push({
            	name :'transactionId',
            	value : details.id
            });
            dataAjax.push({
            	name :'orderId',
            	value : data.orderID
            });
            dataAjax.push({
            	name : 'status',
            	value : details.status
            });
            dataOrder = data;
            // console.log(typeof(data));
            // console.log(data.orderID);
            $.ajax({
            	method:'post',
            	url: '<?php echo Url::to(['/cart/submit-payment','id'=>$order->id])?>' ,
            	data: dataAjax,
            	success:function(response)
            	{
            		alert("Your order has been successfully done.");
            		window.location.href = '';
            	}

            })

            // const transaction = orderData.purchase_units[0].payments.captures[0];
            // alert(`Transaction ${transaction.status}: ${transaction.id}\n\nSee console for all available details`);
            // When ready to go live, remove the alert and show a success message within this page. For example:
            // const element = document.getElementById('paypal-button-container');
            // element.innerHTML = '<h3>Thank you for your payment!</h3>';
            // Or go to another URL:  actions.redirect('thank_you.html');
        });
        }
    }).render('#paypal-button-container');
</script>
