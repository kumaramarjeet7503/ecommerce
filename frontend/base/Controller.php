<?php

namespace frontend\base;

use common\models\CartItem;
use Yii;


class Controller extends \yii\web\Controller
{

	public function beforeAction($action)
	{
		if(isGuest())
		{
			$cartItems = \Yii::$app->session->get(CartItem::SESSION_KEY,[]);
			$sum = 0;
			foreach($cartItems as $cartItem)
			{
				$sum += $cartItem['quantity'];
			}
		}else
		{
			$sum = CartItem::findbySql("SELECT sum(quantity) FROM cart_items WHERE user_id = :user_id",[':user_id'=> Yii::$app->user->id])->scalar();
		}

		$this->view->params['cartItemCount'] =$sum;
		return parent::beforeAction($action);
	}
}

?>