<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\SerialColumn;
use yii\grid\GridView;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $searchModel backend\models\search\ProductSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Products';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="product-index">

    <div class="row">
        <div class="col-md-8">
            <h1><?= Html::encode($this->title) ?></h1>
        </div>
        <div class="col-md-4 text-right">
            <p>
                <?= Html::a('Create Product', ['create'], ['class' => 'btn btn-primary']) ?>
            </p>
        </div>
    </div>

    <?php Pjax::begin(); ?>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [          
            [ 
            'class' => SerialColumn::className(),
            'contentOptions'=>['style'=>'width:5%']
        ],
            'name',
            [
                'attribute'=> 'description',
                'format'=>'html',
                'contentOptions'=>['style'=>'width:25%']
            ],
            [
                'attribute'=>'image',
               'label'=>'Image',
               'contentOptions'=>['style'=>'width : 10%'],
               'content'=>function($model)
               {
                    return Html::img($model->getImageUrl(),['style'=>'width:50px']);
               },
              
            ],
           [
                'attribute'=>'price',
                'format' => 'currency',
                'contentOptions'=>['style'=>'width : 12%']
            ],
            [
                'attribute'=>'status',
                'contentOptions'=>['style'=>'width:10%'],
                'content'=>function($model)
               {
                    return Html::tag('span',$model->status ? 'published' : 'draft', ['class'=> $model->status ? 'badge badge-success' : 'badge badge-danger' ]);
               },

            ],
            [
                'attribute'=>'created_at',
                'format' => 'datetime',
                'contentOptions'=>['style'=>'width:12%']
            ],
            //'created_by',
            //'modified_at',
            //'modified_by',
            [
                'class' => ActionColumn::className(),
                'contentOptions'=>['style'=>'width:7%'],
                'header'=>'Action',
                'urlCreator' => function ($action,  $model, $key, $index, $column) {
                    return Url::toRoute([$action, 'id' => $model->id]);
                }
            ],
        ],
    ]); ?>

    <?php Pjax::end(); ?>

</div>
