<?php

use yii\helpers\Html;
use kartik\form\ActiveForm;
use kartik\builder\Form;
use yii\helpers\Url;
use kartik\widgets\Select2;

$all_cont = \frontend\modules\cabinet\models\UserContacts::find()->
        where(['u_id' => $usr_id])->
        orderBy('c_id ASC')->
        all(); 

$cont_list = [];

foreach ($all_cont as $one_cont) {
        $cont_list[$one_cont->c_id] = $one_cont->c_name;
    }

echo Form::widget([
        'model' => $formm,
        'form' => $form,        
        'columns' => 2,
        'attributes' => [// 2 column layout
            'c_id' => [
                'items' => $cont_list,
                'label' => 'Укажите контакт',
                'type' => Form::INPUT_DROPDOWN_LIST,
                'options' => []
            ],
        ]
    ]);