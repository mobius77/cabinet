<section class="content-header">
    <h1>
        Користувач: <?= $model->user_firstname ?>
    </h1>
</section>
<br> 
<div class="row">
    <div class="col-lg-6">
        <?= Yii::$app->controller->renderPartial('form_user', ['object' => $object, 'formm'=>$formm, 'obj_id' => $obj_id, 'model' => $model]) ?>
    </div>
</div>
<br>    
<?= Yii::$app->controller->renderPartial('chat', ['object' => $object, 'obj_id' => $obj_id, 'model' => $model, 'chat' => $chat]) ?>