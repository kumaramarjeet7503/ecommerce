<?php

namespace frontend\controllers;

use Yii;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use common\models\CartItem;
use common\models\Product;
use common\models\Order;
use common\models\OrderAddress;
use yii\web\NotFoundHttpException;
use yii\web\BadRequestHttpException;
use yii\filters\ContentNegotiator;
use PayPalCheckoutSdk\Core\SandboxEnvironment;
use PayPalCheckoutSdk\Core\PayPalHttpClient;
use PayPalCheckoutSdk\Orders\OrdersGetRequest;

class CartController extends \frontend\base\Controller
{
	public function behaviours()
	{
		return [
			[
				'class' => ContentNegotiator::class,
				'only' => ['add'],
				'formats' => [
					'application/json' => Response::FORMAT_JSON,
				],
			],
			[
				'class'=>VerbFilter::class,
				'actions'=>
				[
					'delete'=>
					[
						'POST','DELETE'
					],
					'create-order'=>
					[
						'POST'
					]
				],

			],
			'access'=>[
				'class' => AccessControl::className(),
				'rules' => [
					'actions' => [''],
					'allow' =>true,
					'roles' =>['?']
				]
			],
		];
	}

	public function actionIndex()
	{
		$cartItem = CartItem::getItemsForUser(currUserId());

		return $this->render('index',['items'=>$cartItem]);
	}

	public function actionAdd()
	{
		$id = \Yii::$app->request->post('id');
		$product = Product::find()->id($id)->published()->one();
		if(!isset($product))
		{
				// $this->logMessage($product);
			throw new NotFoundHttpException('Product not exist.');
		}

		if(isGuest())
		{
			$cartItems = \Yii::$app->session->get(CartItem::SESSION_KEY,[]);
			$found = false;
			foreach ($cartItems as  &$item) {
				if($item['id']== $id)
				{
					$item['quantity']++;
					$found = true;
					break;
				}	
			}
			if(!$found)
			{
				$cartItem = [
					'id'=>$id,
					'name'=>$product->name,
					'price'=>$product->price,
					'quantity'=>1,
					'image'=>$product->image,
					'totalPrice'=>$product->price 
				];
				$cartItems[] = $cartItem;
			}
			\Yii::$app->session->set(CartItem::SESSION_KEY,$cartItems);

		}else
		{
			$userId = Yii::$app->user->id;
			$cartItem = CartItem::find()->UserId($userId)->ProductId($id)->one();
			if($cartItem)
			{
				$cartItem->quantity++;
			}else
			{
				$cartItem = new CartItem();
				$cartItem->product_id = $id;
				$cartItem->user_id = $userId;
				$cartItem->quantity = 1;
			}
			if($cartItem->save())
			{
				return json_encode(['success'=>true]);
			}else
			{
				return json_encode(['success'=>false, 'error'=>$CartItem->error]);
			}
		}


	}

	public function actionDelete($id)
	{
		if(isGuest())
		{
			$cartItems = \Yii::$app->session->get(CartItem::SESSION_KEY,[]);
			foreach ($cartItems as $i => $cartItem) {
				if($cartItem['id'] == $id)
				{
					array_splice($cartItems, $i, 1);
				}
			}
			\Yii::$app->session->set(CartItem::SESSION_KEY,$cartItems);
		}
		else
		{
			CartItem::deleteAll(['product_id'=>$id, 'user_id'=>currUserId()]);
		}
		return $this->redirect('index');
	}

	public function actionChangeQuantity()
	{
		$id = \Yii::$app->request->post('id');
		$product = Product::find()->id($id)->published()->one();
		if(!$product)
		{
			throw new NotFoundHttpException("Product not available.");	
		}
		$quantity = \Yii::$app->request->post('quantity');
		if(isGuest())
		{
			$cartItems = \Yii::$app->session->get(CartItem::SESSION_KEY,[]);
			foreach($cartItems as &$cartItem)
			{
				if($cartItem['id'] == $id )
				{
					$cartItem['quantity'] = $quantity;
					break;
				}
			}
			\Yii::$app->session->set(CartItem::SESSION_KEY,$cartItems);
		}
		else
		{
			$cartItems = CartItem::find()->UserId(currUserId())->productId($id)->one();
			if($cartItems)
			{
				$cartItems->quantity = $quantity;
				$cartItems->save();
			}
		}
		return json_encode(CartItem::getTotalQuantityForUser(currUserId()));
	}

	public function actionCheckout()
	{
		$cartItems = CartItem::getItemsForUser(currUserId());
		if(empty($cartItems))
		{
			return $this->redirect([Yii::$app->homeUrl]);
		}

		
		$productQuantity = CartItem::getTotalQuantityForUser(currUserId()); 
		$totalPrice = CartItem::getTotalPriceForUser(currUserId());
		$order = new Order();
		$orderAddress = new orderAddress();


		if(Yii::$app->request->post())
		{
			$order->status =   Order::STATUS_DRAFT;
			$order->total_price = CartItem::getTotalPriceForUser(currUserId());
			$order->created_at = time();
			$order->created_by = currUserId();

			$transaction = Yii::$app->db->begintransaction();
			if($order->load(Yii::$app->request->post()) 
				&& $order->save()
				&& $order->saveOrderItems()
				&& $order->saveAddress(Yii::$app->request->post()))
			{		
				$transaction->commit();
				CartItem::clearCartItems(currUserId());

				return $this->render('pay-now',
					[
						'order'=>$order,
					]);
			}
		}

		if(!isGuest())
		{
			$user = Yii::$app->user->identity;
			$userAddress = $user->getAddress();

			$order->firstname = $user->firstname;
			$order->lastname = $user->lastname;
			$order->email = $user->email;
			$order->mobile_no = $user->mobile_no;
			$order->status = Order::STATUS_DRAFT;

			$orderAddress->address = $userAddress->address;
			$orderAddress->city = $userAddress->city;
			$orderAddress->state = $userAddress->state;
			$orderAddress->country = $userAddress->country;
			$orderAddress->pincode = $userAddress->pincode;
		}

		return $this->render('checkout',[
			'order'=>$order,
			'orderAddress'=>$orderAddress,
			'cartItems'=>$cartItems,
			'productQuantity'=>$productQuantity,
			'totalPrice'=>$totalPrice
		]);
	}

	public function actionSubmitPayment()
	{
		$orderId = $_GET['id'];
		$where = ['id'=>$orderId,'status'=>Order::STATUS_DRAFT];
		if(!isGuest())
		{
			$where['created_by'] = currUserId();
		}

		$order = Order::find($where)->one();
		if(!$order)
		{
			throw new NotFoundHttpException() ;
		}

		$paypalOrderId = Yii::$app->request->post('orderId');

		$exists = Order::find()->andWhere(['paypal_order_id'=>$paypalOrderId])->exists();
		if($exists)
		{
			throw new BadRequestHttpException();
		}

		$environment = new SandboxEnvironment(Yii::$app->params['paypalClientId'], Yii::$app->params['paypalSecretId']);
		$client = new PayPalHttpClient($environment);

		$response = $client->execute(new OrdersGetRequest($paypalOrderId));

		if($response->statusCode === 200)
		{
			$order->paypal_order_id = $paypalOrderId;
			$paidAmount = 0;
			foreach ($response->result->purchase_units as $purchase_units) {
				
				if($purchase_units->amount->currency_code === 'USD')
				{
					$paidAmount += $purchase_units->amount->value;
				}
			}

			if($paidAmount === $order->total_price && $response->result->status === 'COMPLETED' )
			{
				$order->status = Order::STATUS_COMPLETED;
			}

			$order->transaction_id = $response->result->purchase_units[0]->payments->captures[0]->id;

			if($order->save())
			{
				return json_encode(['success'=>true]);
			}
			else
			{
				Yii::error("Order was not saved. Data:".VarDumper::dumpAsString($order->errors));
			}
		}
		throw new BadRequestHttpException();
	}

	public function actionCreateOrder()
	{
		

		$transactionId = Yii::$app->request->post('transactionId');
		$status = Yii::$app->request->post('status');

		$order = new Order();
		$orderAddress = new OrderAddress();

		$order->transaction_id = $transactionId;
		$order->status =  $status === 'COMPLETED' ? Order::STATUS_COMPLETED : STATUS_FAILURED;
		$order->total_price = CartItem::getTotalPriceForUser(currUserId());
		$order->created_at = time();
		$order->created_by = currUserId();

		$transaction = Yii::$app->db->begintransaction();
		if($order->load(Yii::$app->request->post()) 
			&& $order->save()
			&& $order->saveOrderItems()
			&& $order->saveAddress(Yii::$app->request->post()))
		{		
			$transaction->commit();
			CartItem::clearCartItems(currUserId());
			return json_encode([
				'success'=>true
			]);
		}
		else
		{
			$transaction->rollback();
			return json_encode([
				'success'=>'false',
				'error' => $order->errors
			]);
		}
	}

	public function logMessage($obj)
	{
		error_log(print_r($obj,true),3,'C:\xampp\htdocs\ecommerce\frontend\runtime\logs\cart.log');
	}
}


?>