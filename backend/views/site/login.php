<?php

/** @var yii\web\View $this */
/** @var yii\bootstrap4\ActiveForm $form */
/** @var \common\models\LoginForm $model */

use yii\bootstrap4\ActiveForm;
use yii\bootstrap4\Html;

$this->title = 'Login';
?>
<!-- <div class="site-login">
    <div class="mt-5 offset-lg-3 col-lg-6">
        <h1><?= Html::encode($this->title) ?></h1>

        <p>Please fill out the following fields to login:</p>

        <?php $form = ActiveForm::begin(['id' => 'login-form']); ?>

            <?= $form->field($model, 'username')->textInput(['autofocus' => true]) ?>

            <?= $form->field($model, 'password')->passwordInput() ?>

            <?= $form->field($model, 'rememberMe')->checkbox() ?>

            <div class="form-group">
                <?= Html::submitButton('Login', ['class' => 'btn btn-primary btn-block', 'name' => 'login-button']) ?>
            </div>

        <?php ActiveForm::end(); ?>
    </div>
</div> -->

 <div class="row">
                            <div class="col-lg-6 d-none d-lg-block bg-login-image"></div>
                            <div class="col-lg-6">
                                <div class="p-5">
                                    <div class="text-center">
                                        <h1 class="h4 text-gray-900 mb-4">Welcome Back!</h1>
                                    </div>

                                     <?php $form = ActiveForm::begin([
                                        'id' => 'login-form',
                                        'options'=>['class'=>'user'],
                                    ]); ?>
                                    

            <?= $form->field($model, 'username',['inputOptions'=>['class'=>'form-control form-control-user']])->textInput(['autofocus' => true,'placeholder'=>'username']) ?>

            <?= $form->field($model, 'password',['inputOptions'=>['class'=>'form-control form-control-user']])->passwordInput(['placeholder'=>'password']) ?>

            <?= $form->field($model, 'rememberMe')->checkbox() ?>
          
                                         <?= Html::submitButton('Login', ['class' => 'btn btn-primary btn-user btn-block', 'name' => 'login-button']) ?>
     <!--                                    <hr>
                                        <a href="index.html" class="btn btn-google btn-user btn-block">
                                            <i class="fab fa-google fa-fw"></i> Login with Google
                                        </a>
                                        <a href="index.html" class="btn btn-facebook btn-user btn-block">
                                            <i class="fab fa-facebook-f fa-fw"></i> Login with Facebook
                                        </a> -->
                            <?php ActiveForm::end(); ?>
                                    <hr>
                                    <div class="text-center">
                                        <a class="small" href="/site/forget-password">Forgot Password?</a>
                                    </div>
                            
    </div>
