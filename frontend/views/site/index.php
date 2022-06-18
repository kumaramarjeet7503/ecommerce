<?php

/** @var yii\web\View $this */

use yii\widgets\ListView;

$this->title = 'My Yii Application';
?>
<div class="site-index">
    <div class="body-content">
        <div class="row">
                <?= ListView::widget([
                    'dataProvider'=>$dataProvider,
                    'layout' => '<div class="row">{items}</div>{pager}',
                    'itemView' => '_product_item',
                    'pager'=>[
                        'class'=> \yii\bootstrap4\LinkPager::class
                    ],
                    'options' =>['class'=>'row'],
                    'itemOptions'=>[
                        'class' => 'col-md-4 mb-4'
                    ]
                ]); ?>
        </div>

    </div>
</div>
