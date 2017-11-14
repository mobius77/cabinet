<?php

use yii\helpers\Html;
use kartik\form\ActiveForm;
use kartik\builder\Form;
use yii\helpers\Url;
use kartik\widgets\Select2;
?>

<section class="content-header" >
    <h4>
        <?php 
        if ( isset($model) ) { $btn_lbl = 'Обновить' ?>
        Редактировать стаус заказа: <span class=""><?= $model->s_name ?></span>
        <?php } else { $btn_lbl = 'Сохранить' ?>
        Добавить статус    
        <?php } ?>
    </h4>
    <ol class="breadcrumb" style="font-size: 14px;">
        <li><a href="/cabinet"><i class="fa fa-home"></i> Главная</a></li>
        <?php 
        if ( isset($model) ) { ?>
        <li class="active">Стаус заказа: <?= $model->s_name ?></li> 
        <?php } else { ?>
        <li class="active">Добавить статус</li> 
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
                        's_name' => [
                            'columnOptions' => ['colspan' => 1],
                            'type' => Form::INPUT_TEXT,
                            'options' => ['placeholder' => '']
                        ],
                    ]
                ]);

                $colors = ['red' => 'Красный', 'blue' => 'Синий', 'green' => 'Зеленый'];
                /*
                echo Form::widget([
                    'model' => $formm,
                    'form' => $form,
                    'columns' => 2,
                    'attributes' => [// 2 column layout
                        's_color' => [
                            'type' => Form::INPUT_WIDGET,
                            'widgetClass' => '\kartik\select2\Select2',
                            'columnOptions' => ['colspan' => 2],
                            'label' => 'Цвет',
                            'options' => [
                                'options' => [
                                    'placeholder' => 'Выберите цвет',
                                    'autocomplete' => 'off',
                                ],
                                'data' => $colors,
                                'pluginOptions' => [
                                    'allowClear' => false,
                                ],
                            ],
                        ],
                    ]
                ]);     */
                
                echo Form::widget([
                    'model' => $formm,
                    'form' => $form,
                    'columns' => 2,
                    'attributes' => [// 2 column layout
                        's_color' => [
                            'type' => Form::INPUT_WIDGET,
                            'widgetClass' => '\kartik\widgets\ColorInput',
                            'columnOptions' => ['colspan' => 2],
                            'label' => 'Цвет',
                            'options' => [
                                'options' => [
                                    'placeholder' => 'Выберите цвет',
                                    'autocomplete' => 'off',
                                ],                                
                                'pluginOptions' => [
                                    'allowClear' => false,
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

