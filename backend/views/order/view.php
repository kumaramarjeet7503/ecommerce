<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use common\models\Order;

/* @var $this yii\web\View */
/* @var $model common\models\Order */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Orders', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="order-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Delete', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'total_price',
                [
                'attribute'=>'status',
                'contentOptions'=>['style'=>'width:15% '],
                'format'=>'html',
                'value'=> function($model)
                {
                   if($model->status == Order::STATUS_COMPLETED)
                    {
                        return Html::tag('span','completed',['class'=>'badge badge-success']);
                    }
                    else if($model->status == Order::STATUS_DRAFT)
                    {
                        return Html::tag('span','unpaid',['class'=>'badge badge-secondary']);
                    }elseif ($model->status == Order::STATUS_PAID) {
                        return Html::tag('span','paid',['class'=> 'badge badge-primary']);
                    }else
                    {
                        return Html::tag('span','failure',['class'=> 'badge badge-danger']);
                    }
                }
            ],
            'firstname',
            'lastname',
            'email:email',
            'mobile_no',
            'transaction_id',
            'paypal_order_id',
            'created_at',
            'created_by',
        ],
    ]) ?>

    <h4>Order Items</h4>
    <table class="table table-sm">
        <thead>
        <tr>
            <th>Image</th>
            <th>Name</th>
            <th>Quantity</th>
            <th>Unit Price</th>
            <th>Total Price</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($model->orderItems as $item): ?>
            <tr>
                <td>
                    <img src="<?php echo $item->product ? $item->product->getImageUrl() : \common\models\Product::formatImageUrl(null) ?>"
                         style="width: 50px;">
                </td>
                <td><?php echo $item->product_name ?></td>
                <td><?php echo $item->quantity ?></td>
                <td><?php echo Yii::$app->formatter->asCurrency($item->unit_price) ?></td>
                <td><?php echo Yii::$app->formatter->asCurrency($item->quantity * $item->unit_price) ?></td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>

</div>
