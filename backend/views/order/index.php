<?php

use yii\bootstrap4\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;
use common\models\Order;
use yii\bootstrap4\LinkPager;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\search\OrderSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Orders';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="order-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'id'=>'order_table',
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'pager'=>['class'=>LinkPager::class],
        'columns' => [
            ['attribute'=>'id','contentOptions'=>['style'=>'width:10% ']],
            [
                'attribute'=>'fullname',
                'contentOptions'=>['style'=>'width:20% '],
                'value'=> function($model)
                {
                    return $model->firstname.' '.$model->lastname;
                }
            ],
            [
                'attribute'=>'total_price',
                'format'=>'currency',
                'contentOptions'=>['style'=>'width:15% ']
            ],
             [
                'attribute'=>'status',
                'contentOptions'=>['style'=>'width:15% '],
                'content'=> function($model)
                {
                    if($model->status == Order::STATUS_DRAFT)
                    {
                        return Html::tag('span','unpaid',['class'=>'badge badge-secondary']);
                    }elseif ($model->status == Order::STATUS_COMPLETED) {
                        return Html::tag('span','completed',['class'=> 'badge badge-success']);
                    }else
                    {
                        return Html::tag('span','failure',['class'=> 'badge badge-danger']);
                    }
                }
            ],
            [
                'attribute'=>'created_at',
                'format'=>'datetime',
                'contentOptions'=>['style'=>'width:20%']
            ],
            //'created_by',
            [
                'class' => ActionColumn::className(),
                'contentOptions'=>['style'=>'width:10%'],
                'buttonOptions'=>['class'=>'action'],
                'template'=>'{view}{delete}',
                'urlCreator' => function ($action, $model, $key, $index, $column) {
                    return Url::toRoute([$action, 'id' => $model->id]);
                 }
            ],
        ],
    ]); ?>


</div>
