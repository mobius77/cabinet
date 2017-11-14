<?php

use yii\helpers\Html;
use kartik\form\ActiveForm;
use kartik\builder\Form;
use yii\helpers\Url;
use kartik\widgets\Select2;
use kartik\widgets\DepDrop;
use yii\helpers\ArrayHelper;

$curr_usr = \common\models\User::findOne(Yii::$app->user->id);

?>

<section class="content-header" >
    <h4>
        Добавить адрес
    </h4>
    <ol class="breadcrumb" style="font-size: 14px;">
        <li><a href="/cabinet"><i class="fa fa-home"></i> Главная</a></li>
        <li><a href="/cabinet/usr/usr-contacts">Адреса</a></li>        
        <li class="active">Добавить адрес</li>
    </ol>
</section>

<section class="content">
<div class="row">
    <div class="col-lg-12">
        
        <?= $this->render('form_adress', [
        'c_id' => $c_id, 'u_id' => $u_id, 'formm' => $formm, 'model' => $model, 'way' => $way, 'act'=>'add'
    ]) ?>
        

    </div>
</div>
</section>

