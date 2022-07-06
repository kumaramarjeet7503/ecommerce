<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "cart_items".
 *
 * @property int $id
 * @property int $product_id
 * @property int $quantity
 * @property int $user_id
 *
 * @property Product $product
 * @property User $user
 */
class CartItem extends \yii\db\ActiveRecord
{
    const SESSION_KEY = 'CART_ITEMS';
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'cart_items';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['product_id', 'quantity', 'user_id'], 'required'],
            [['product_id', 'quantity', 'user_id'], 'integer'],
            [['product_id'], 'exist', 'skipOnError' => true, 'targetClass' => Product::className(), 'targetAttribute' => ['product_id' => 'id']],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['user_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'product_id' => 'Product ID',
            'quantity' => 'Quantity',
            'user_id' => 'User ID',
        ];
    }

    /**
     * Gets query for [[Product]].
     *
     * @return \yii\db\ActiveQuery|\common\models\query\ProductQuery
     */
    public function getProduct()
    {
        return $this->hasOne(Product::className(), ['id' => 'product_id']);
    }

    /**
     * Gets query for [[User]].
     *
     * @return \yii\db\ActiveQuery|\common\models\query\UserQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }

    public static function getTotalQuantityForUser($currUserId)
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
            $sum = CartItem::findbySql("SELECT sum(quantity) FROM cart_items WHERE user_id = :user_id",[':user_id'=>$currUserId])->scalar();
        }

        return $sum;
    }

        public static function getTotalPriceForUser($currUserId)
    {
        if(isGuest())
        {
            $cartItems = \Yii::$app->session->get(CartItem::SESSION_KEY,[]);
            $sum = 0;
            foreach($cartItems as $cartItem)
            {
                $sum += $cartItem['quantity']*$cartItem['price'];
            }
        }else
        {
            $sum = CartItem::findbySql("SELECT sum(quantity * price) FROM cart_items WHERE user_id = :user_id",[':user_id'=>$currUserId])->scalar();
        }

        return $sum;
    }

    /**
     * {@inheritdoc}
     * @return \common\models\query\CartItemQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new \common\models\query\CartItemQuery(get_called_class());
    }

    public static function getItemsForUser($currUserId)
    {
        return CartItem::findBySql("
                SELECT 
                c.product_id as id,
                p.name,
                p.image,
                p.price,
                c.quantity,
                p.price * c.quantity as totalPrice
                FROM cart_items as c 
                LEFT JOIN products as p on p.id = c.product_id
                WHERE c.user_id = :userId", [':userId' => $currUserId]
            )->asArray()->all();

    }


}
