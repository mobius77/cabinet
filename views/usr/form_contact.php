<?php
use yii\helpers\Html;
use kartik\form\ActiveForm;
use kartik\builder\Form;
use yii\helpers\Url;
use kartik\widgets\Select2;

?>

<section class="content">
<div class="row">
    <div class="col-lg-12">
        <div class="box box-solid" style="padding: 15px;">
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
                'c_famil' => [
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
                'c_name' => [
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
                'c_otch' => [
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
                'c_email' => ['type' => Form::INPUT_TEXT, 'options' => ['placeholder' => '']],
            ]
        ]);



        echo Form::widget([
            'model' => $formm,
            'form' => $form,
            'columns' => 1,
            'columnSize' => 'md',
            'attributes' => [
                'c_phone' => ['type' => Form::INPUT_TEXT, 'options' => ['placeholder' => '']],
            ]
        ]);

        echo Form::widget([
            'model' => $formm,
            'form' => $form,
            'columns' => 1,
            'columnSize' => 'md',
            'attributes' => [
                'c_post' => ['type' => Form::INPUT_TEXT, 'options' => ['placeholder' => '']],
            ]
        ]);
        
        echo Form::widget([
            'model' => $formm,
            'form' => $form,
            'columns' => 1,
            'columnSize' => 'md',
            'attributes' => [
                'c_type' => ['type' => Form::INPUT_WIDGET,
                    'widgetClass'=>'\kartik\widgets\SwitchInput',                                
                    'options' => [                                    
                        'pluginOptions'=>[
                            'handleWidth'=>90,
                            'onText'=>'Юр. лицо',
                            'offText'=>'Физ. лицо'
                        ],
                        'class' => 'fld_norm']],
            ]
        ]);
        
        echo Form::widget([
            'model' => $formm,
            'form' => $form,
            'columns' => 1,
            'columnSize' => 'md',
            'attributes' => [
                'c_edr' => ['type' => Form::INPUT_TEXT, 'options' => ['placeholder' => '']],
            ]
        ]);

        echo Form::widget([
            'model' => $formm,
            'form' => $form,
            'columns' => 1,
            'columnSize' => 'md',
            'attributes' => [
                'c_note' => ['type' => Form::INPUT_TEXT, 'options' => ['placeholder' => '']],
            ]
        ]);


        echo Form::widget([
            'model' => $formm,
            'form' => $form,
            'columns' => 2,
            'attributes' => [// 2 column layout
                'c_id' => [
                    'label' => false,
                    'type' => Form::INPUT_HIDDEN,
                    'options' => ['value' => $formm->c_id]
                ],
            ]
        ]);

        if (!isset($u_id))
        {
            $u_id = $formm->u_id;
        }
            
        
        echo Form::widget([
            'model' => $formm,
            'form' => $form,
            'columns' => 2,
            'attributes' => [// 2 column layout
                'u_id' => [
                    'label' => false,
                    'type' => Form::INPUT_HIDDEN,
                    'options' => ['value' => $u_id]
                ],
            ]
        ]);
       
        if ($act == 'upd') {
        echo Form::widget([
            'model' => $formm,
            'form' => $form,
            'columns' => 1,
            'columnSize' => 'md',
            'attributes' => [
                'a_flag' => ['type' => Form::INPUT_WIDGET,
                    'widgetClass'=>'\kartik\widgets\SwitchInput',                                
                    'options' => [                                    
                        'pluginOptions'=>[
                            'handleWidth'=>90,
                            'onText'=>'Основной',
                            'offText'=>'Не основной'
                        ],
                        'class' => 'fld_norm']],
            ]
        ]);
        
        }


        
            ?>
        
       
    
            <div style="text-align: right;">
        <?php
         
         switch ($way) {
            case 'canc':
                echo Html::Button('Отмена', ['class' => 'btn btn-error', 'onclick' => 'window.history.back();return false;']);
                break;
            case 'back':
                echo Html::a('Вернуться', [Url::previous()], ['class' => 'btn btn-default']);
                break;
            default:
                echo Html::a('Вернуться', [Url::previous()], ['class' => 'btn btn-default']);
                break;            
          
         }
         
          echo Html::submitButton( $act=='add' ? 'Сохранить' : 'Обновить', ['class' => 'btn btn-primary', 'style' => ['margin-left' => '15px']]);  
         ?>
       </div> 
        </div>
    </div>
</div>
<?php ActiveForm::end(); ?>
 
</section>

<?= 'ВОТ ТУТ СПИСОК АДРЕСОВ ткущего контакта' ?>