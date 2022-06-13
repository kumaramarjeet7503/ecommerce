<?php

namespace common\models;

use Yii;
use common\models\Product;
use yii\behaviors\BlameableBehavior;
use yii\behaviors\TimestampBehavior;
use yii\db\Expression;
use yii\helpers\FileHelper;

/**
 * This is the model class for table "products".
 *
 * @property int $id
 * @property string $name
 * @property string|null $description
 * @property string|null $image
 * @property float $price
 * @property int $status
 * @property int|null $created_at
 * @property int|null $created_by
 * @property int|null $modified_at
 * @property int|null $modified_by
 *
 * @property CartItems[] $cartItems
 * @property User $createdBy
 * @property User $modifiedBy
 * @property OrderItems[] $orderItems
 */
class Product extends \yii\db\ActiveRecord
{

    /**
    *@var \yii\web\UploadedFile
    **/

    public $imageFile;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'products';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name', 'price', 'status','image'], 'required'],
            [['description'], 'string'],
            [['price'], 'number'],
            [['imageFile'],'image','extensions'=>['png','jpg','jpeg','webp'],'maxSize'=>5*1024*1024],
            [['status', 'created_at', 'created_by', 'modified_at', 'modified_by'], 'integer'],
            [['name'], 'string', 'max' => 255],
            [['image'], 'string'],
            [['created_by'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['created_by' => 'id']],
            [['modified_by'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['modified_by' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'description' => 'Description',
            'image' => 'Image',
            'price' => 'Price',
            'status' => 'Published',
            'created_at' => 'Created At',
            'created_by' => 'Created By',
            'modified_at' => 'Modified At',
            'modified_by' => 'Modified By',
            'imageFile' => 'Product Image',
        ];
    }

    /**
     * Gets query for [[CartItems]].
     *
     * @return \yii\db\ActiveQuery|\common\models\query\CartItemsQuery
     */
    public function getCartItems()
    {
        return $this->hasMany(CartItems::className(), ['product_id' => 'id']);
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
     * Gets query for [[ModifiedBy]].
     *
     * @return \yii\db\ActiveQuery|\common\models\query\UserQuery
     */
    public function getModifiedBy()
    {
        return $this->hasOne(User::className(), ['id' => 'modified_by']);
    }

    /**
     * Gets query for [[OrderItems]].
     *
     * @return \yii\db\ActiveQuery|\common\models\query\OrderItemsQuery
     */
    public function getOrderItems()
    {
        return $this->hasMany(OrderItems::className(), ['product_id' => 'id']);
    }

    /**
     * {@inheritdoc}
     * @return \common\models\query\ProductQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new \common\models\query\ProductQuery(get_called_class());
    }

    public function behaviors()
    {
        return [
            [
                'class'=>BlameableBehavior::class,
                'updatedByAttribute'=>'modified_by'
            ],
            [
                'class'=>TimeStampBehavior::class,
                'updatedAtAttribute'=>'modified_at'
            ]
            
        ];
    }

    public function save($runValidation=false,$attributeNames=null)
    {
        $transaction = Yii::$app->db->beginTransaction();
        if($this->imageFile)
        {
            $this->image = '/products/'.Yii::$app->security->generateRandomString(32).'/'.$this->imageFile->name;
            print_r($this->image);
           
        }

         $ok = parent::save($runValidation,$attributeNames);
        if($ok)
        {
            $fullPath = Yii::getAlias('@frontend/web/storage'.$this->image);
            $dir = dirname($fullPath);
            if(FileHelper::createDirectory($dir) ||  !$this->imageFile->saveAs($fullPath))
            {
                $transaction->rollBack();
                return false;
            }
                $transaction->commit();
        }
        return $ok;
 }

public function getImageUrl()
{
    $imageUrl = Yii::$app->params['frontendUrl'];
    return $imageUrl.'/storage'.$this->image;
}

}
