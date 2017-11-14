<?php

use yii\helpers\Html;
use kartik\form\ActiveForm;
use kartik\builder\Form;
use yii\helpers\Url;

?>

<section class="content-header" >
    <h4>
        <?php 
        if ( isset($model) ) { $btn_lbl = 'Обновить' ?>
        Редактировать группу клиентов: <span class=""><?= $model->ug_name ?></span>
        <?php } else { $btn_lbl = 'Сохранить' ?>
        Добавить группу клиентов 
        <?php } ?>
    </h4>
    <ol class="breadcrumb" style="font-size: 14px;">
        <li><a href="/cabinet"><i class="fa fa-home"></i> Главная</a></li>
        <?php 
        if ( isset($model) ) { ?>
        <li ><a href="/cabinet/prop/usr-groups">Группы клиентов </a></li><li class="active"> <?= $model->ug_name ?></li> 
        <?php } else { ?>
        <li class="active">Добавить группу клиентов</li> 
        <?php } ?>
              
    </ol>

</section>
<section class="content">

    <div class="row">
        <div class="col-lg-12">
            <div class="box box-solid" style="padding-top: 15px; padding-right: 15px;">
                <?php
                $form = ActiveForm::begin(['type' => ActiveForm::TYPE_HORIZONTAL, /* 'enableAjaxValidation'=>true, */
                            'enableClientValidation' => true,
                            'options' => [
                                'enctype' => 'multipart/form-data',
                            ],
                ]);
                ?>

                <?php
                echo Form::widget([
                    'model' => $formm,
                    'form' => $form,
                    'columns' => 4,
                    'attributes' => [
                        'ug_name' => [
                            'columnOptions' => ['colspan' => 1],
                            'type' => Form::INPUT_TEXT,
                            'options' => ['placeholder' => '']
                        ],
                    ]
                ]);
                
                echo Form::widget([
                    'model' => $formm,
                    'form' => $form,
                    'columns' => 4,
                    'attributes' => [
                        'ug_skidka' => [
                            'columnOptions' => ['colspan' => 1],
                            'type' => Form::INPUT_TEXT,
                            'options' => ['placeholder' => '']
                        ],
                    ]
                ]);     
                
                echo Form::widget([
                    'model' => $formm,
                    'form' => $form,
                    'columns' => 4,
                    'attributes' => [
                        'ug_price' => [
            'type'=>Form::INPUT_WIDGET, 
            'widgetClass'=>'\kartik\widgets\Select2',
            'columnOptions'=>['colspan'=>1],
            'options' => [
                'options' => [
                    'autocomplete'=>'off',
                    ],
                'data' => [1=>'Цена сайта',2=>'Цена дилера'],
                'pluginOptions' => [
                    'allowClear'=>false,
                ],
            ],
        ],
                    ]
                ]);   
                
                ?>
            </div>
                <?php
                echo Html::submitButton($btn_lbl, ['class' => 'btn btn-primary', 'style' => ['margin-right' => '5px']]);

                if ($way == 'canc') {
                    echo Html::Button('Отмена', ['class' => 'btn btn-error', 'onclick' => 'window.history.back();return false;']);
                }

                if ($way == 'back') {
                    echo Html::a('Вернуться', [Url::previous()], ['class' => 'btn btn-success']);
                }
                ?>


                <?php ActiveForm::end(); ?>
            </div>
        </div>    
</section>

