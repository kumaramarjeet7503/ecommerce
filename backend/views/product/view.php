<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\Product */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Products', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="product-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
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
            'name',
            'description:html',
            [
                'attribute'=>'image',
               'label'=>'Image',
               'format' =>['html'],
               'value'=>function($model)
               {
                    return Html::img($model->getImageUrl(),['style'=>'width:50px']);
               },
              
            ],
            [
                'attribute'=>'price',
                'format' => 'currency',
            ],
          [
                'attribute'=>'status',
                'format'=>['html'],
                'value' => function($model)
                {
                    return Html::tag('span',$model->status ? 'published':'draft',['class'=>$model->status ? 'badge badge-success': 'badge badge-danger']);
                }
            ],
            'created_at:date',
            'createdBy.username',
            'modified_at:date',
            'updatedBy.email',
        ],
    ]) ?>

</div>
