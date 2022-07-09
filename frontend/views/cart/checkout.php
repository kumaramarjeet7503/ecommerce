<?php 

use yii\bootstrap4\Html;
use yii\bootstrap4\ActiveForm;
use yii\helpers\Url;
?>



<script src="https://www.paypal.com/sdk/js?client-id=ASkKestAqo0_uYeXlQVPsafWMrg-QY0Wlmbum3w3WyIXWa6xcM_0uXViBM1LF0TplCEc1dJq8ao6Vuym&currency=USD"></script>

<div class="row">
	<div class="col">
		<?php $form = ActiveForm::begin([
			'id'=>'checkout-form',	
		]); ?>
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


<?php ActiveForm::end() ?>

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
				<div id="paypal-button-container"></div>
			</div>
		</div>
	</div>
</div>


<script>
	paypal.Buttons({
        // Sets up the transaction when a payment button is clicked
        createOrder: (data, actions) => {
        	return actions.order.create({
        		purchase_units: [{
        			amount: {
                value: <?php echo $totalPrice?> // Can also reference a variable or function
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
            	url: '<?php echo Url::to(['/cart/create-order'])?>' ,
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
