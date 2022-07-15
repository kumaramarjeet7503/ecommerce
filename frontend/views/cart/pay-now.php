<?php



use yii\bootstrap4\Html;
use yii\helpers\Url;

$orderAddress = $order->orderAddresses;

?>

<script src="https://www.paypal.com/sdk/js?client-id=ASkKestAqo0_uYeXlQVPsafWMrg-QY0Wlmbum3w3WyIXWa6xcM_0uXViBM1LF0TplCEc1dJq8ao6Vuym&currency=USD"></script>

<div class="row">
	<div class="col">
		<h4>Account Information</h4>
		<table class="table">
				<tr>
					<th>Firstname</th>
					<td><?php echo $order->firstname?></td>
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
		<table class="table table-sm">
			<thead>
				<tr>
					<th>Address</th>
					<th>City</th>	
					<th>State</th>	
					<th>Country</th>
					<th>PinCode</th>
				</tr>
			</thead>
			<tbody>
				<tr>
					<td class="text-right"><?php echo $orderAddress->address?></td>

					<td class="text-right"><?php echo $orderAddress->city?></td>

					<td class="text-right"><?php echo $orderAddress->state?></td>

					<td class="text-right"><?php echo $orderAddress->country?></td>

					<td class="text-right"><?php echo  $orderAddress->pincode?></td>
				</tr>
			</tbody>
		</table>
	</div>

	<div class="col">
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
						<td><img src='<?php $item->product->getImageUrl()?>' style='width: 50px'></td>
						<td><?php echo $item->product_name ?></td>
						<td><?php echo $item->quantity ?></td>
						<td><?php echo $item->unit_price * $item->quantity ?></td>						
					</tr>
				</tbody>
				<?php endforeach ; ?>
			</table>
			<hr>
			<table class="table">
				<th>Total Item</th>
				<td><?php echo $order->getItemsQuantity() ?></td>
				<th>Total Price</th>
				<td><?php echo $order->total_price?></td>
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
        	return actions.order.capture().then(function(data) {
            // console.log(data.id);
            // console.log('Capture result', orderData, JSON.stringify(orderData, null, 2));
            const $form = $('#checkout-form');
            var dataAjax = $form.serializeArray();
            // console.log("data",dataAjax);
            dataAjax.push({
            	name :'transactionId',
            	value : data.id
            });
            dataAjax.push({
            	name : 'status',
            	value : data.status
            });

            $.ajax({
            	method:'post',
            	url: '<?php echo Url::to(['/cart/submit-payment',['id'=>$order->id]])?>' ,
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
