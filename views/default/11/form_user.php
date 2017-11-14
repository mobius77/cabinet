<?php

use yii\helpers\Html;
use kartik\form\ActiveForm;
use kartik\builder\Form;
use yii\helpers\Url;

 $form = ActiveForm::begin(['type' => ActiveForm::TYPE_VERTICAL, /*'enableAjaxValidation'=>true, */
        'enableClientValidation' => true,
     'options' => [
                      
                        
                        'enctype'=>'multipart/form-data',
                    ],
     ]);

if (Yii::$app->user->identity->role>1) {
    

   echo Form::widget([
                'model'=>$formm,
                'form'=>$form,
                'columns'=>6,
                'attributes'=>[       
                    'status'=>[
                        'type'=>Form::INPUT_WIDGET, 
                        'widgetClass'=>'\kartik\widgets\Select2',
                        'columnOptions'=>['colspan'=>2],
                        'options' => [
                            'options' => [
                                'placeholder' => '...',
                                'autocomplete'=>'off',
                                ],
                            'data' => ['0'=>'Новий','10'=>'Активний'],
                            'pluginOptions' => [
                                'allowClear'=>false,
                            ],
                        ],
                    ],
                ]
            ]);
} 

 
 
    echo Form::widget([
        'model'=>$formm,
        'form'=>$form,
        'columns'=>4,
        'attributes'=>[
            'user_firstname'=>[
                'columnOptions' => ['colspan' => 1],
                'type'=>Form::INPUT_TEXT, 
                'options'=>['placeholder'=>'']
                ],
        ]
    ]);
    
    echo Form::widget([
                    'model' => $formm,
                    'form' => $form,
                    'columns' => 1,
                    'columnSize' => 'md',
                    'attributes' => [
                    'user_city' => ['type' => Form::INPUT_TEXT, 'options' => ['placeholder' => '' ]],
                    ]
                ]);
    
    echo Form::widget([
                    'model' => $formm,
                    'form' => $form,
                    'columns' => 1,
                    'columnSize' => 'md',
                    'attributes' => [
                        'user_index' => ['type' => Form::INPUT_TEXT, 'options' => ['placeholder' => '' ]],
                    ]
                ]);
    
    echo Form::widget([
                    'model' => $formm,
                    'form' => $form,
                    'columns' => 1,
                    'columnSize' => 'md',
                    'attributes' => [
                        'user_adress_1' => ['type' => Form::INPUT_TEXT, 'options' => ['placeholder' => '' ]],
                    ]
                ]);
    
    echo Form::widget([
                    'model' => $formm,
                    'form' => $form,
                    'columns' => 1,
                    'columnSize' => 'md',
                    'attributes' => [
                        'user_company' => ['type' => Form::INPUT_TEXT, 'options' => ['placeholder' => '' ]],
                    ]
                ]);
    
    echo Form::widget([
                    'model' => $formm,
                    'form' => $form,
                    'columns' => 1,
                    'columnSize' => 'md',
                    'attributes' => [
                       
                        'user_doc' => [
                            'type' => Form::INPUT_WIDGET, 
                            'widgetClass' => \kartik\widgets\FileInput::classname(),
                            'options' => [
                                
                                'pluginOptions'=>[
                                    'placeholder' => '',
                                    'pluginLoading' => false,
                                    'showPreview' => false,
                                    'showCaption' => true,
                                    'showRemove' => true,
                                    'showUpload' => false,
                                    
                                    ],
                             
                                ]],
                    ]
                ]);
    if ($formm->user_doc!='') {
        ?>
<p><a href="/profile/files/<?= $formm->user_doc ?>"><?= $formm->user_doc ?></a></p><br><br>
<?php
    }
    
     echo Form::widget([
                    'model' => $formm,
                    'form' => $form,
                    'columns' => 1,
                    'columnSize' => 'md',
                    'attributes' => [
                        'user_tel' => ['type' => Form::INPUT_TEXT, 'options' => ['placeholder' => '' ]],
                    ]
                ]);
         
    
    echo Form::widget([
                    'model' => $formm,
                    'form' => $form,
                    'columns' => 1,
                    'columnSize' => 'md',
                    'attributes' => [
                        'password' => ['type' => Form::INPUT_PASSWORD,
                            'options' =>
                            ['placeholder' => '', 'value' => md5('captain_teemo_on_duty') ]
                            
                            ],
                    ]
                ]);
    
        
     
    echo Form::widget([
        'model' => $formm,
        'form' => $form,
        'columns' => 2,
        'attributes' => [       // 2 column layout
            'id' => [
                'label' => '',
                'type' => Form::INPUT_HIDDEN,
                'options' => ['value' => $formm->id]
            ],
        ]
    ]);

    ?>

    <div class="form-group">
        <?= Html::submitButton('Оновити', ['class' => 'btn btn-primary']) ?>
        <?= Html::Button('Відмінити', ['class' => 'btn btn-error', 'onclick' => 'window.history.back();return false;']) ?>
    </div>

    <?php ActiveForm::end(); ?>