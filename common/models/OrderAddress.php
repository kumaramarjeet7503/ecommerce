<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "order_addresses".
 *
 * @property int $order_id
 * @property string|null $address
 * @property string|null $city
 * @property string|null $state
 * @property string|null $country
 * @property int $pincode
 *
 * @property Orders $order
 */
class OrderAddress extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'order_addresses';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['order_id', 'pincode'], 'required'],
            [['order_id', 'pincode'], 'integer'],
            [['address'], 'string'],
            [['city'], 'string', 'max' => 250],
            [['state', 'country'], 'string', 'max' => 100],
            [['order_id'], 'unique'],
            [['order_id'], 'exist', 'skipOnError' => true, 'targetClass' => Orders::className(), 'targetAttribute' => ['order_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'order_id' => 'Order ID',
            'address' => 'Address',
            'city' => 'City',
            'state' => 'State',
            'country' => 'Country',
            'pincode' => 'Pincode',
        ];
    }

    /**
     * Gets query for [[Order]].
     *
     * @return \yii\db\ActiveQuery|\common\models\query\OrdersQuery
     */
    public function getOrder()
    {
        return $this->hasOne(Orders::className(), ['id' => 'order_id']);
    }

    /**
     * {@inheritdoc}
     * @return \common\models\query\OrderAddressQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new \common\models\query\OrderAddressQuery(get_called_class());
    }
}
