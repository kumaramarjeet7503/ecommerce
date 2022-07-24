<?php

use yii\helpers\Html;
use yii\bootstrap4\ActiveForm;
use dosamigos\ckeditor\CKEditor;

/* @var $this yii\web\View */
/* @var $model common\models\Product */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="product-form">

    <?php $form = ActiveForm::begin(['options'=>['enctype'=> 'multipart/form-data']]); ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'description')->widget(CKEditor::className(),['options'=>['rows'=>6],
        'preset'=>'basic'
    ]) ?>



    <?= $form->field($model, 'imageFile',['template'=>
      '<div class="custom-file">
      {label}
        {input}
        {error}
        </div>',
        'labelOptions'=>['class'=>'custom-file-label'],
        'inputOptions'=>['class'=>'custom-file-input']
        ])->textInput(['type'=>'file']) ?>
  

    <?= $form->field($model, 'price')->textInput(['maxlength' => true,'type'=>'number','step'=>'0.01']) ?>

    <?= $form->field($model, 'status')->Checkbox() ?>

<div class="row">
    <div class="col-md-6">
        <?= Html::a('cancel',['product/index'],['class' => 'btn btn-primary']) ?>
    </div>
    <div class="col-md-6 text-right"> 
        <?= Html::submitButton('Save', ['class' => 'btn btn-primary']) ?>
    </div>
    </div>

    <?php ActiveForm::end(); ?>

</div>
