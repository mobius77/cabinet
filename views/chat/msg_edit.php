<?php 
use kartik\form\ActiveForm;
use kartik\builder\Form;
use yii\helpers\Html;
use common\models\User;
use frontend\modules\cabinet\models\ConfMessages;
use frontend\modules\cabinet\models\ConfList;
use frontend\modules\cabinet\models\ConfUsers;
use kartik\dialog\Dialog;
?>



<?php

$form = ActiveForm::begin(['type' => ActiveForm::TYPE_HORIZONTAL, /* 'enableAjaxValidation'=>true, */
            'enableClientValidation' => true,
            'id' => 'form_chat_msg',
            'options' => [
                'enctype' => 'multipart/form-data',
            ],
        ]);

echo $form->field($formm, 'cm_text', [
    'addon' => [
        'append' => [
            'content' => Html::submitButton('<i class="fa fa-level-up"></i>', ['class' => 'btn btn-primary']),
            'asButton' => true
        ]
    ]
]);



ActiveForm::end();
