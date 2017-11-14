<?php

use yii\helpers\Html;
use kartik\form\ActiveForm;
use kartik\builder\Form;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;


/* @var $this yii\web\View */
/* @var $model common\models\Clients */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="clients-form">

    <?php  $form = ActiveForm::begin(['type'=>ActiveForm::TYPE_VERTICAL, /*'enableAjaxValidation'=>true, */'enableClientValidation' => true,]);


    echo Form::widget([
        'model'=>$model,
        'form'=>$form,
        'columns'=>1,
        'attributes'=>[
            'templ_name'=>['type'=>Form::INPUT_TEXT, 'options'=>['placeholder'=>'']],
        ]
    ]);

    echo Form::widget([
        'model'=>$model,
        'form'=>$form,
        'columns'=>1,
        'attributes'=>[
            'templ_anons'=>['type'=>Form::INPUT_TEXTAREA, 'options'=>['placeholder'=>'']],
        ]
    ]);

    echo Form::widget([
        'model'=>$model,
        'form'=>$form,
        'columns'=>1,
        'attributes'=>[
            'templ_descr'=>['type'=>Form::INPUT_TEXTAREA, 'options'=>['placeholder'=>'']],
        ]
    ]);

    echo Form::widget([
        'model'=>$model,
        'form'=>$form,
        'columns'=>1,
        'attributes'=>[
            'templ_cond'=>['type'=>Form::INPUT_TEXTAREA, 'options'=>['placeholder'=>'']],
        ]
    ]);


    echo Form::widget([
        'model'=>$model,
        'form'=>$form,
        'columns'=>2,
        'attributes'=>[       // 2 column layout
            'user_id'=>[
                'label'=>'',
                'type'=>Form::INPUT_HIDDEN,
                'options'=>['value'=>$user_id]
            ],
        ]
    ]);

    ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Создать' : 'Обновить', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
        <?= Html::Button('Отмена', ['class' => 'btn btn-error', 'onclick'=>'window.history.back();return false;']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
