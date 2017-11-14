<?php

use yii\helpers\Html;
use kartik\form\ActiveForm;
use kartik\builder\Form;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use kartik\select2\Select2;
use kartik\depdrop\DepDrop;
use common\models\Params;

/* @var $this yii\web\View */
/* @var $model common\models\Clients */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="clients-form col-lg-3">

    <?php $form = ActiveForm::begin(['type' => ActiveForm::TYPE_VERTICAL, /*'enableAjaxValidation'=>true, */
        'enableClientValidation' => true,]);

    if (!isset($dop)) $dop=0;
    
    if ($dop==0) {

        $tm1 = [$time => $time];

        $srt_data = [$time + 2 => $time + 2, $time + 1 => $time + 1];
        $time_val = $time + 2;
    }
    else{
        $tm1 = [$time+1 => $time+1];
        $srt_data = [$time + 2 => $time + 2];
    }


    echo Form::widget([
        'model' => $model,
        'form' => $form,
        'columns' => 3,
        'columnSize' => 'md',
        'attributes' => [
            'order_date' => ['type'=>Form::INPUT_TEXT, 'options'=>['disabled'=>false, 'readonly'=>true,'placeholder'=>'']],
         ]
    ]);
    
     echo Form::widget([
        'model' => $model,
        'form' => $form,
        'columns' => 3,
        'columnSize' => 'md',
        'attributes' => [
            'order_time_start' => [
                'type'=>Form::INPUT_WIDGET,
                'widgetClass' => '\kartik\select2\Select2',
                'columnOptions' => ['colspan' => 1],
                'options' => [
                    'options' => [
                        'autocomplete' => 'off',
                    ],
                    'data' => $tm1,
                    'hideSearch' => true,
                    'pluginOptions' => [
                        'allowClear' => false,
                    ],
                ],
            ],
        ]
    ]); 
    
       echo Form::widget([
        'model' => $model,
        'form' => $form,
        'columns' => 3,
        'columnSize' => 'md',
        'attributes' => [
            'order_time_end' => [
                'type'=>Form::INPUT_WIDGET,
                'widgetClass'=>'\kartik\depdrop\DepDrop',
                'columnOptions'=>['colspan'=>1],
                'options' => [
                    'data'=> $srt_data,

                    'options' => ['value' => 17,],
                    'type' => DepDrop::TYPE_SELECT2,
                    'value' => 17,
                    'select2Options'=>['hideSearch' => true,'value' => 17,'pluginOptions'=>['allowClear'=>false],],
                    'pluginOptions'=>[
                        'depends'=>['order-order_time_start'],
                        'url' => Url::to(['get-schedule-endtime']),
                        'loadingText' => 'Загрузка времени ...',
                    ],
                ],
            ],
        ]
    ]);

if ($model->templ_id!=null) {
    $uid = $model->user_id;
}
else
{
    $uid = $user_id; 
}
       
    echo Form::widget([
        'model' => $model,
        'form' => $form,
        'columns' => 3,
        'columnSize' => 'md',
        'attributes' => [
            'templ_id' => [
                'type'=>Form::INPUT_WIDGET,
                'widgetClass' => '\kartik\select2\Select2',
                'columnOptions' => ['colspan' => 2],
                'options' => [
                    'options' => [
                        'placeholder' => 'Выберите шаблон ...',
                        'autocomplete' => 'off',
                    ],
                    'data' => ArrayHelper::map(\common\models\StreamTemplate::find()->where('user_id='.$uid)->asArray()->all(), 'templ_id', 'templ_name'),
                    'pluginOptions' => [
                        'allowClear' => true,
                    ],
                ],
            ],
        ]
    ]);

    if (Yii::$app->user->identity->role==10) {
        if ($model->order_pf==null) {
           $model->order_pf = Params::find()->where('p_name="pf"')->one()->p_value;
        }
    
     echo Form::widget([
        'model' => $model,
        'form' => $form,
        'columns' => 2,
        'attributes' => [       // 2 column layout
            'order_pf' => [
                'type' => Form::INPUT_TEXT,
            ],
        ]
    ]);
    
    }
    else {
        
        if ($model->order_pf!=null) {
          $pf=  $model->order_pf;
        }
        else{
           $pf = Params::find()->where('p_name="pf"')->one()->p_value;
        }
        
         echo Form::widget([
        'model' => $model,
        'form' => $form,
        'columns' => 2,
        'attributes' => [       // 2 column layout
            'order_pf' => [
                'label' => '',
                'type' => Form::INPUT_HIDDEN,
                'options' => ['value' => $pf]
            ],
        ]
    ]);
        
    }
     
    echo Form::widget([
        'model' => $model,
        'form' => $form,
        'columns' => 2,
        'attributes' => [       // 2 column layout
            'user_id' => [
                'label' => '',
                'type' => Form::INPUT_HIDDEN,
                'options' => ['value' => $uid]
            ],
        ]
    ]);

    ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Создать' : 'Обновить', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
        <?= Html::Button('Отмена', ['class' => 'btn btn-error', 'onclick' => 'window.history.back();return false;']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
