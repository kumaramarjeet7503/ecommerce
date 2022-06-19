<?php

/** @var yii\web\View $this */
/** @var yii\bootstrap4\ActiveForm $form */
/** @var \frontend\models\SignupForm $model */

use yii\bootstrap4\Html;
use yii\bootstrap4\ActiveForm;
use yii\widgets\Pjax;

$this->title = 'User Details';

?>

<div class="row">
	<div class="col-md-6">
		<div class="card">
			<div class="card-header">
				Address Information
			</div>
			<div class="card-body">
				<?php Pjax::begin([
					'enablePushState'=>false
				]); ?>
				<?php echo $this->render('user_address',['userAddress'=>$userAddress]) ?>
				<?php Pjax::end(); ?>
			</div>
		</div>
	</div>

	<div class="col-md-6">
		<div class="card">
			<div class="card-header">
				Account Information
			</div>
			<div class="card-body">
				<?php Pjax::begin([
					'enablePushState'=>false
				]); ?>
				<?php echo $this->render('user_account',['user'=>$user]) ?>
				<?php Pjax::end(); ?>
			</div>
		</div>
	</div>
</div>

