<?php

namespace frontend\base;

use common\models\CartItem;
use Yii;


class Controller extends \yii\web\Controller
{
	public function beforeAction($action)
	{
		$this->view->params['cartItemCount'] = CartItem::findbySql("SELECT sum(quantity) FROM cart_items WHERE user_id = :user_id",[':user_id'=> Yii::$app->user->id])->scalar();
		return parent::beforeAction($action);
	}
}

?>