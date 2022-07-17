<?php

namespace common\models;

use Yii;
use common\models\CartItem;
/**
 * This is the model class for table "orders".
 *
 * @property int $id
 * @property float $total_price
 * @property int $status
 * @property string $firstname
 * @property string|null $lastname
 * @property string $email
 * @property int|null $mobile_no
 * @property string|null $transaction_id
 * @property int|null $created_at
 * @property int|null $created_by
 * @property string|null $paypal_order_id
 * @property User $createdBy
 * @property OrderAddress $orderAddress
 * @property OrderItems[] $orderItems
 */
class Order extends \yii\db\ActiveRecord
{
    const STATUS_DRAFT = 0;
    const STATUS_COMPLETED = 1;
    const STATUS_FAILURED = 2;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'orders';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['total_price', 'status', 'firstname', 'email'], 'required'],
            [['total_price'], 'number'],
            [['status', 'mobile_no', 'created_at', 'created_by'], 'integer'],
            [['firstname', 'lastname'], 'string', 'max' => 45],
            [['email', 'transaction_id','paypal_order_id'], 'string', 'max' => 255],
            [['created_by'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['created_by' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'total_price' => 'Total Price',
            'status' => 'Status',
            'firstname' => 'Firstname',
            'lastname' => 'Lastname',
            'email' => 'Email',
            'mobile_no' => 'Mobile No',
            'transaction_id' => 'Transaction ID',
            'created_at' => 'Created At',
            'created_by' => 'Created By',
        ];
    }

    /**
     * Gets query for [[CreatedBy]].
     *
     * @return \yii\db\ActiveQuery|\common\models\query\UserQuery
     */
    public function getCreatedBy()
    {
        return $this->hasOne(User::className(), ['id' => 'created_by']);
    }

    /**
     * Gets query for [[OrderAddresses]].
     *
     * @return \yii\db\ActiveQuery|\common\models\query\OrderAddressesQuery
     */
    public function getOrderAddresses()
    {
        return $this->hasOne(OrderAddress::className(), ['order_id' => 'id']);
    }

    /**
     * Gets query for [[OrderItems]].
     *
     * @return \yii\db\ActiveQuery|\common\models\query\OrderItemsQuery
     */
    public function getOrderItems()
    {
        return $this->hasMany(OrderItem::className(), ['order_id' => 'id']);
    }

    /**
     * {@inheritdoc}
     * @return \common\models\query\OrderQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new \common\models\query\OrderQuery(get_called_class());
    }

    public function saveOrderItems()
    {
        $cartItems = CartItem::getItemsForUser(currUserId());
        foreach ($cartItems as $cartItem) {
            $orderItem = new OrderItem();
            $orderItem->product_name = $cartItem['name'];
            $orderItem->product_id = $cartItem['id'];
            $orderItem->unit_price = $cartItem['price'];
            $orderItem->quantity = $cartItem['quantity'];
            $orderItem->order_id = $this->id ;
            if(!$orderItem->save())
            {
                throw new Exception("Database Exception error".implode($orderItem->getFirstErrors()));
            }
        }
        return true;
    }

    public function saveAddress($postData)
    {
        $orderAddress = new OrderAddress();
        $orderAddress->order_id = $this->id;
        if($orderAddress->load($postData) && $orderAddress->save())
        {
            return true;
        }
        throw new Exception("Exception in :".implode($orderAddress->getFirstErrors()));
        
    }

    public function getItemsQuantity()
    {
     $sum = CartItem::findbySql("SELECT sum(quantity) FROM order_items WHERE order_id = :order_id",[':order_id'=>$this->id])->scalar();
     return $sum;
 }

 public function sendEmailToVendor()
 {
    return Yii::$app->mailer->compose(
        ['html'=>'order-completed-vendor-html','text'=>'order-completed-vendor-text'],
        ['order'=>$this]
    )
    ->setFrom([Yii::$app->params['supportEmail'] => Yii::$app->name])
    ->setTo(Yii::$app->params['vendorEmail'])
    ->setSubject('New Order has been made at :'.Yii::$app->name)
   ->send();
}

public function sendEmailToCustomer()
{
    return Yii::$app->mailer->compose(
        ['html'=>'order-completed-customer-html','text'=>'order-completed-customer-text'],
        ['order'=>$this]
    )
    ->setFrom([Yii::$app->params['supportEmail'] => Yii::$app->name])
    ->setTo($this->email)
    ->setSubject('Your Order has been confirmed at :'.Yii::$app->name)
    ->send();
}

}
