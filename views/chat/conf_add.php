<?php

use yii\helpers\Html;
use kartik\form\ActiveForm;
use kartik\builder\Form;
use yii\helpers\Url;
use kartik\widgets\Select2;
use common\models\User;

$user_all = User::find()->
        where('role > 1')->
        andWhere('id != ' . Yii::$app->user->id . '')->
        orderBy('user_firstname ASC')->
        all();

if ($user_all != NULL) {
    $user_list =[];
    
    foreach ($user_all as $user_one) {
        $user_list[$user_one->id] = $user_one->user_firstname;
    }
}

?>

<section class="content-header" >
    <h4>
        <?php
        if (isset($model)) {
            $btn_lbl = 'Обновить'
            ?>
            Редактировать конференцию: <span class=""><?= $model->s_name ?></span>
            <?php
        } else {
            $btn_lbl = 'Сохранить'
            ?>
            Создать конференцию    
        <?php } ?>
    </h4>
    <ol class="breadcrumb" style="font-size: 14px;">
        <li><a href="/cabinet"><i class="fa fa-home"></i> Главная</a></li>
        <?php if (isset($model)) { ?>
            <li class="active">Стаус заказа: <?= $model->s_name ?></li> 
        <?php } else { ?>
            <li class="active">Создать конференцию </li> 
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
                        'cf_name' => [
                            'columnOptions' => ['colspan' => 1],
                            'type' => Form::INPUT_TEXT,
                            'options' => ['placeholder' => '']
                        ],
                    ]
                ]);

                
                echo $form->field($formm, 'cf_users')->widget(Select2::classname(), [
                    'data' => $user_list,
                    'showToggleAll' => false,
                    'options' => [
                        'placeholder' => 'Выберите участников',
                        'multiple' => true,                        
                        ],
                    'pluginOptions' => [
                        'allowClear' => true,
                        
                    ],
                ]);
                
               echo Form::widget([
                'model' => $formm,
                'form' => $form,
                'columns' => 2,
                'attributes' => [// 2 column layout
                    'cf_flag' => [
                        'label' => false,
                        'type' => Form::INPUT_HIDDEN,
                        'options' => ['value' => 1]
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

