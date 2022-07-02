<?php

namespace frontend\controllers;

use Yii;
use yii\web\Controller;
use  yii\filters\VerbFilter;
use yii\filters\AccessControl;
use common\models\CartItem;
use common\models\Product;
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
				'actions'=>['delete'=>
				[
					'POST','DELETE'
				]
			]
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
		if(\Yii::$app->user->isGuest)
		{
			$cartItem = \Yii::$app->session->get(CartItem::SESSION_KEY,[]);
		}
		else
		{
			$cartItem = CartItem::findBySql('
				SELECT c.product_id as id,
				p.name,
				p.image,
				p.price,
				c.quantity,
				p.price * c.quantity as totalPrice
				FROM cart_items as c 
				LEFT JOIN products as p on p.id = c.product_id
				WHERE c.user_id = :userId', [':userId' => \Yii::$app->user->id]
			)->asArray()->all();
		}

		return $this->render('index',['items'=>$cartItem]);
	}

	public function actionAdd()
	{
		$id = \Yii::$app->request->post('id');
		$product = Product::find()->id($id)->published()->one();
		if(!isset($product))
		{
				$this->logMessage($product);
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
			$this->logMessage($cartItems);
			\Yii::$app->session->set(CartItem::SESSION_KEY,$cartItems);
		}
		else
		{
			$cartitems = CartItem::find()->UserId(currUserId)->productId($id)->one();
			if($cartItems)
			{
				$cartItems->quantity = $quantity;
				$cartItems->save();
			}
		}

		return json_encode(CartItem::getTotalQuantityForUser(currUserId()));
	
	}

	public function logMessage($obj)
	{
		error_log(print_r($obj,true),3,'C:\xampp\htdocs\ecommerce\frontend\runtime\logs\cart.log');
	}
}


?>