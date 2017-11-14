<?php
use yii\helpers\Html;
use kartik\form\ActiveForm;
use kartik\builder\Form;
use yii\helpers\Url;
use kartik\widgets\Select2;
use yii\helpers\ArrayHelper;
use frontend\modules\cabinet\models\UserContacts;
use yii\web\JsExpression;

?>

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
        
        if (!Yii::$app->authManager->getAssignment('admin', Yii::$app->user->getId())) {
                    $u_id = Yii::$app->user->id;
                    $formm->u_id = Yii::$app->user->id;
                }
        
                
                if (isset($formm->a_city)) {
                    $city = \common\models\Geography::findOne($formm->a_city);
                    $city_init = $city->g_c_name.' ('.$city->g_o_name.' '.$city->g_r_name.')';
                } else { 
                    $city_init = '';
                }

                echo Form::widget([
                    'model' => $formm,
                    'form' => $form,
                    'columns' => 2,
                    'attributes' => [// 2 column layout
                        'a_city' => [
                            'type' => Form::INPUT_WIDGET,
                            'widgetClass' => '\kartik\select2\Select2',
                            'columnOptions' => ['colspan' => 2],
                           
                            'options' => [
                                'initValueText' => $city_init,
                                'options' => [
                                    'placeholder' => '',
                                    'autocomplete' => 'off',
                                ],
                               /* 'data' => $city_list,*/
                                'pluginOptions' => [
                                    'allowClear' => false,
                                    'minimumInputLength' => 3,
                                    'ajax' => [
                                        'url' => \yii\helpers\Url::to(['citylist']),
                                        'dataType' => 'json',
                                        'data' => new JsExpression('function(params) { return {q:params.term}; }')
                                    ],
                                    'escapeMarkup' => new JsExpression('function (markup) { return markup; }'),
                                    'templateResult' => new JsExpression('function(geography) { return geography.text; }'),
                                    'templateSelection' => new JsExpression('function (geography) { return geography.text; }'),
                                ],
                            ],
                        ],
                    ]
                ]);        
                
                
    /*    echo Form::widget([
            'model' => $formm,
            'form' => $form,
            'columns' => 4,
            'attributes' => [
                'a_city' => [
                    'columnOptions' => ['colspan' => 1],
                    'type' => Form::INPUT_TEXT,
                    'options' => ['placeholder' => '']
                ],
            ]
        ]);*/

        echo Form::widget([
            'model' => $formm,
            'form' => $form,
            'columns' => 1,
            'columnSize' => 'md',
            'attributes' => [
                'a_adr' => ['type' => Form::INPUT_TEXT, 'options' => ['placeholder' => '']],
            ]
        ]);
        
        echo Form::widget([
            'model' => $formm,
            'form' => $form,
            'columns' => 1,
            'columnSize' => 'md',
            'attributes' => [
                'a_note' => ['type' => Form::INPUT_TEXT, 'options' => ['placeholder' => '']],
            ]
        ]);
        
        echo Form::widget([
            'model' => $formm,
            'form' => $form,
            'columns' => 2,
            'attributes' => [// 2 column layout
                'u_id' => [
                    'label' => '',
                    'type' => Form::INPUT_HIDDEN,
                    'options' => ['value' => $formm->u_id]
                ],
            ]
        ]);
        
       
    if ($act == 'add') {
        if ($c_id > 0 && $c_id != 'empty') {

            echo Form::widget([
            'model' => $formm,
            'form' => $form,
            'columns' => 2,
            'attributes' => [// 2 column layout
                'c_id' => [
                    'label' => '',
                    'type' => Form::INPUT_HIDDEN,
                    'options' => ['value' => $c_id]
                ],
            ]
        ]);

        } else {    

                $cont_list = ArrayHelper::map(UserContacts::find()->where('u_id='.$u_id)->asArray()->all(), 'c_id', 'c_name');

                echo Form::widget([
                    'model' => $formm,
                    'form' => $form,
                    'columns' => 2,
                    'attributes' => [// 2 column layout
                        'c_id' => [
                            'type' => Form::INPUT_WIDGET,
                            'widgetClass' => '\kartik\select2\Select2',
                            'columnOptions' => ['colspan' => 2],
                            'label' => 'Контакт',
                            'options' => [
                                'options' => [
                                    'placeholder' => 'Выберите контакт',
                                    'autocomplete' => 'off',
                                ],
                                'data' => $cont_list,
                                'pluginOptions' => [
                                    'allowClear' => false,
                                ],
                            ],
                        ],
                    ]
                ]);
            }
    }
        
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
       
<?php ActiveForm::end(); ?>
        </div>