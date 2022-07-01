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
			// [
			// 	'class'=>ContentNegotiater::class,
			// 	'only'=> ['add'],
			// 	'formats'=>[
			// 		'application/json' => Response::FORMAT_JSON
			// 	],
			// ],
			[
				'class' => ContentNegotiator::class,
				'only' => ['add'],
				'formats' => [
					'application/json' => Response::FORMAT_JSON,
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
		if(\Yii::$app->user->isGuest)
		{
			$cartItem = \Yii::$app->session->get(CartItem::SESSION_KEY,[]);
		}
		else
		{
			$cartItem = CartItem::findBySql('
				SELECT c.product_id,
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
				if($item['product_id']== $id)
				{
					$item['quantity']++;
					$found = true;
					break;
				}	
			}
			if(!$found)
				{
					$cartItem = [
						'product_id'=>$id,
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
				if($cartItem['product_id'] == $id)
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

	public function logMessage($obj)
	{
		error_log(print_r($obj,true),3,'C:\xampp\htdocs\ecommerce\frontend\runtime\logs\cart.log');
	}
}


?>