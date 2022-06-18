<?php 

/** @var \common\models\Product $model */

?>


<div class="card h-100">
    <!-- Sale badge-->
    <div class="badge bg-dark text-white position-absolute" style="top: 0.5rem; right: 0.5rem">Sale</div>
    <!-- Product image-->
    <img class="card-img-top" src="<?php echo $model->getImageUrl()?>" >
    <!-- Product details-->
    <div class="card-body p-4">
    	<hr style="background: #333">
        <div class="text-center">
            <!-- Product name-->
            <h5 class="fw-bolder"><?php echo $model->name ?></h5>
            <!-- Product reviews-->
            <div class="d-flex justify-content-center small text-warning mb-2">
               <?php echo $model->getShortDesc() ?>
            </div>
            <!-- Product price-->
            <span class="text-muted text-decoration-line-through"></span>
            <div class="font-weight-bold">
            <?php echo Yii::$app->formatter->asCurrency($model->price) ?>
        	</div>
        </div>
    </div>
    <!-- Product actions-->
    <div class="card-footer p-4 pt-0 border-top-0 ">
        <div class="text-center"><a class="btn btn-primary mt-auto" href="#">Add to cart</a></div>
    </div>
</div>
