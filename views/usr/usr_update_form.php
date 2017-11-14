<?php

use yii\helpers\Html;
use kartik\form\ActiveForm;
use kartik\builder\Form;
use yii\helpers\Url;
use kartik\widgets\Select2;
use kartik\tabs\TabsX;
use frontend\modules\cabinet\models\UserGroups;

if ($model->status == 10) {
    $nm_color = 'text-primary';
} else {
    $nm_color = 'text-danger';
}

$u_gr = UserGroups::find()->where(['ug_id' => $model->gender])->one();
?>

<?php if ($b_crumb != 'adm') { ?>
    <section class="content-header" >
        <h4>
            Профиль пользователя: <span class="<?= $nm_color ?>"><?= $model->username ?></span>        
        </h4>
        <h5>Группа: <span class="text-primary"> <?= $u_gr->ug_name ?></span></h5>
        <ol class="breadcrumb" style="font-size: 14px;">
            <li><a href="/cabinet"><i class="fa fa-home"></i> Главная</a></li>
            <li class="active">Профиль пользователя: <?= $model->username ?></li>       
        </ol>

    </section>
    <section class="content">
    <?php } ?>    

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
               
                
                if (Yii::$app->authManager->getAssignment('admin', Yii::$app->user->getId())) {

                    $sel_data_s = [0 => 'Заблокирован', 10 => 'Активен'];

                    echo Form::widget([
                        'model' => $formm,
                        'form' => $form,
                        'columns' => 4,
                        'attributes' => [
                            'status' => [
                                'columnOptions' => ['colspan' => 1],
                                'type' => Form::INPUT_WIDGET,
                                'options' => ['data' => $sel_data_s],
                                'widgetClass' => '\kartik\widgets\Select2',
                            ],
                        ]
                    ]);

                    $u_groups = [];

                    $u_gr_all = UserGroups::find()->orderBy('ug_id')->all();

                    foreach ($u_gr_all as $u_gr_one) {
                        $u_groups[$u_gr_one->ug_id] = $u_gr_one->ug_name;
                    }

                    echo Form::widget([
                        'model' => $formm,
                        'form' => $form,
                        'columns' => 4,
                        'attributes' => [
                            'gender' => [
                                'columnOptions' => ['colspan' => 1],
                                'type' => Form::INPUT_WIDGET,
                                'options' => ['data' => $u_groups],
                                'widgetClass' => '\kartik\widgets\Select2',
                            ],
                        ]
                    ]);
                    
                 
                       
                    
                    
                }
                 echo $form->field($formm, 'user_type')->
                            radioButtonGroup([0 => 'Физ. лицо', 1 => 'Юр. лицо']);
                 
                echo Form::widget([
                    'model' => $formm,
                    'form' => $form,
                    'columns' => 4,
                    'attributes' => [
                        'user_firstname' => [
                            'columnOptions' => ['colspan' => 1],
                            'type' => Form::INPUT_TEXT,
                            'options' => ['placeholder' => '']
                        ],
                    ]
                ]);

                echo Form::widget([
                    'model' => $formm,
                    'form' => $form,
                    'columns' => 1,
                    'columnSize' => 'md',
                    'attributes' => [
                        'user_pasport' => ['type' => Form::INPUT_TEXT, 'options' => ['placeholder' => '']],
                    ]
                ]);



                echo Form::widget([
                    'model' => $formm,
                    'form' => $form,
                    'columns' => 1,
                    'columnSize' => 'md',
                    'attributes' => [
                        'user_adress_1' => ['type' => Form::INPUT_TEXT, 'options' => ['placeholder' => '']],
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
                            ['placeholder' => '', 'value' => md5('captain_teemo_on_duty')]
                        ],
                    ]
                ]);



                echo Form::widget([
                    'model' => $formm,
                    'form' => $form,
                    'columns' => 2,
                    'attributes' => [// 2 column layout
                        'id' => [
                            'label' => false,
                            'type' => Form::INPUT_HIDDEN,
                            'options' => ['value' => $formm->id]
                        ],
                    ]
                ]);
                ?>
            </div>

            <div class="col-lg-12 form-group">
                <?= Html::submitButton('Обновить', ['class' => 'btn btn-primary']) ?>
                <?= Html::Button('Отмена', ['class' => 'btn btn-error', 'onclick' => 'window.history.back();return false;']) ?>
            </div>

            <?php ActiveForm::end(); ?>
        </div>
    </div>
    <?php if ($b_crumb != 'adm') { ?>
    </section>
<?php } ?>
