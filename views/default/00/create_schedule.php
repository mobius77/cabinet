<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\Clients */

$this->title = 'Новая запись';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="clients-create">



    <?= $this->render('_form_schedule', [
        'model' => $model,
        'user_id'=>$id,
        'dt' => $dt,
        'time' => $time,
        'dop'=>$dop
    ]) ?>

</div>
