<?php

namespace frontend\controllers;

use Yii;
use yii\web\Controller;
use  yii\filters\VerbFilter;
use yii\filters\AccessControl;
use common\models\CartItem;
use common\models\Product;
use common\models\Order;
use common\models\OrderAddress;
use yii\web\NotFoundHttpException;
use yii\filters\ContentNegotiator;

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
		$order = new Order();
		$orderAddress = new orderAddress();

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

		$cartItems = CartItem::getItemsForUser(currUserId());
		$productQuantity = CartItem::getTotalQuantityForUser(currUserId()); 
		$totalPrice = CartItem::getTotalPriceForUser(currUserId());

		return $this->render('checkout',[
			'order'=>$order,
			'orderAddress'=>$orderAddress,
			'cartItems'=>$cartItems,
			'productQuantity'=>$productQuantity,
			'totalPrice'=>$totalPrice
		]);
	}

	public function actionCreateOrder()
	{
		$cartItems = cartItem::getItemsForUser(currUserId());
		if($cartItems == null)
		{
			$this->redirect(Yii::$app->homeUrl);
		}

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