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

class CartController extends Controller
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
					'roles' =>['@']
				]
			],
		];
	}

	public function actionIndex()
	{
		if(Yii::$app->user->IsGuest)
		{

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
			return $this->render('index',['items'=>$cartItem]);
		}
	}

	public function actionAdd()
	{
		$id = \Yii::$app->request->post('id');

		$product = Product::find()->id($id)->published()->one();
		if(!$product)
		{
			throw new NotFoundHttpException('Product not exist.');
		}

			if(Yii::$app->user->isGuest)
			{
				//ghgghgjh

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

	public function logMessage($obj)
	{
		error_log(print_r($obj,true),3,'C:\xampp\htdocs\ecommerce\frontend\runtime\logs\cart.log');
	}
}


?>