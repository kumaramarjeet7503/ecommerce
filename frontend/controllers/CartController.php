<?php

namespace frontend\controllers;

use Yii;
use yii\web\Controller;
use  yii\filters\VerbFilter;
use yii\filters\AccessControl;
use common\models\CartItem;

class CartController extends Controller
{
	public function behaviours()
	{
		return [
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

			print_r($cartItem);
			return $this->render('index',['items'=>$cartItem]);
		}
	}
}


?>