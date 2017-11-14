<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\Clients */

$this->title = 'Новый шаблон';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="clients-create">



    <?= $this->render('_form_templ', [
        'model' => $model,
        'user_id'=>$id
    ]) ?>

</div>
