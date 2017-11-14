<?php

use yii\bootstrap\Tabs;
use yii\helpers\Html;
use kartik\form\ActiveForm;
use kartik\builder\Form;

use common\models\SysTemplateField;
use dosamigos\tinymce\TinyMce;
use yii\helpers\ArrayHelper;

$blocks = [
            '0'=>[
                    'name'=>'Реквізити підприємства (організації)',
                    'count'=>8,
                ],
    
            '1'=>[
                    'name'=>'Керівник підприємства (організації)',
                    'count'=>3,
                ],
    
            '2'=>[
                    'name'=>'Контактна особа по інвестиційному проекту',
                    'count'=>3,
                ],
    
            '3'=>[
                    'name'=>'Загальна інформація',
                    'count'=>10,
                ],
    
            '4'=>[
                    'name'=>'Термін окупності проекту (років)',
                    'count'=>2,
                ],

            '5'=>[
                    'name'=>'Показник рентабельності підприємства, %',
                    'count'=>2,
                ],    
    
    ];

$fields = SysTemplateField::find()->where('tm_id = 33')->orderBy('tf_poz')->all();

 $form = ActiveForm::begin(['type' => ActiveForm::TYPE_HORIZONTAL, /*'enableAjaxValidation'=>true, */
        'formConfig' => ['labelSpan' => 3, 'deviceSize' => ActiveForm::SIZE_SMALL],
        'enableClientValidation' => true,
   
     
     ]);

 $el = 0;
 $bl=0;
 


 ?>

<div class="row">
<?php     if (Yii::$app->user->identity->role > 1) { ?>
    <div class="col-lg-3 col-md-6 col-sm-12">
<?php
 
  echo Form::widget([
                'model'=>$model,
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
                            'data' => ['0'=>'Новий','1'=>'Є зауваження','2'=>'Активний'],
                            'pluginOptions' => [
                                'allowClear'=>false,
                            ],
                        ],
                    ],
                ]
            ]);
 
 ?>
        
  </div>
<?php }
else if (Yii::$app->session->get('lang')!='uk')
{
  ?>
    <div class="col-lg-3 col-md-6 col-sm-12">
<?php
 
  echo Form::widget([
                'model'=>$model,
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
                            'data' => ['0'=>'Вимкнений','2'=>'Активний'],
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
}



?>
    <div class="col-lg-3 col-md-6 col-sm-12">
        <label>Url проекта: </label>
<?php

    echo Html::textInput('tree_url', $model->tree->tree_url, []);
    ?>
        
  </div>
    <div class="col-lg-3 col-md-6 col-sm-12">
      
<?php
if ($model->tree_id!='')
   {
    echo Html::a('Редагувати мапу','update-project-map?id='.$model->tree_id, ['class'=>'btn btn-primary','style'=>'margin:5px;']);
    echo Html::a('Додати фото та файли','#filesphoto', ['class'=>'btn btn-primary','style'=>'margin:5px;']);
   }
    ?>
        
  </div>
    
    <div class="col-lg-3 col-md-6 col-sm-12">
<?php
 
  echo Form::widget([
                'model'=>$model,
                'form'=>$form,
                'columns'=>12,
                'attributes'=>[       
                    'p_categ'=>[
                        'type'=>Form::INPUT_WIDGET, 
                        'widgetClass'=>'\kartik\widgets\Select2',
                        'options' => [
                            'options' => [
                                'placeholder' => '...',
                                'autocomplete'=>'off',
                                ],
                            'data' => ArrayHelper::map(\common\models\ValTree38::find()->joinWith('tree')->where('tree.is_enable=1 and lang="uk"')->asArray()->all(), 'tree_id', 'nname'),
                            'pluginOptions' => [
                                'allowClear'=>false,
                            ],
                        ],
                    ],
                ]
            ]);
 
 ?>
        
  </div>
    
    
    </div>
        
 <?php
 
 
 foreach($fields as $field)
 {
    
    if ($el==0) {
        echo '<h3 style="color:#E71461">'.$blocks[$bl]['name'].'</h3>';
        
    }
    
    switch ($field->tf_type)
    {
       case 1:
            echo $form->field($model, $field->tf_name)->widget(TinyMce::className(), [
                'options' => ['rows' => 12],
                'language' => 'uk',
                'clientOptions' => [
                    'plugins' => [
                        "advlist autolink lists link charmap print preview anchor",
                        "searchreplace visualblocks code fullscreen",
                        "insertdatetime media table contextmenu paste"
                    ],
                    'toolbar' => "undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image"
                ]
            ]);
           break;
       
       case 7:
            
            echo Form::widget([
                'model'=>$model,
                'form'=>$form,
                'columns'=>6,
                'attributes'=>[       
                    $field->tf_name=>[
                        'type'=>Form::INPUT_WIDGET, 
                        'widgetClass'=>'\kartik\widgets\Select2',
                        'columnOptions'=>['colspan'=>2],
                        'options' => [
                            'options' => [
                                'placeholder' => '...',
                                'autocomplete'=>'off',
                                ],
                            'data' => ArrayHelper::map(\common\models\ValTree34::find()->joinWith('tree')->where('tree.is_enable=1 and lang="uk"')->asArray()->all(), 'tree_id', 'nname'),
                            'pluginOptions' => [
                                'allowClear'=>true,
                            ],
                        ],
                    ],
                ]
            ]);
       break;  
       
       default:
            echo Form::widget([
                'model'=>$model,
                'form'=>$form,
                'columns'=>4,
                'attributes'=>[
                    $field->tf_name=>[
                        'columnOptions' => ['colspan' => 1],
                        'type'=>Form::INPUT_TEXT, 
                        'options'=>['placeholder'=>'']
                        ],
                ]
            ]);
     }
    
 
    
    $el++;
    if ($el==$blocks[$bl]['count']) {
        $el=0;
        $bl++;
    }
    
 }
    
    
    echo Form::widget([
        'model' => $model,
        'form' => $form,
        'columns' => 2,
        'attributes' => [       // 2 column layout
            'tree_id' => [
                'label' => '',
                'type' => Form::INPUT_HIDDEN,
                'options' => ['value' => $model->tree_id]
            ],
        ]
    ]);

    ?>

<div class="" style="text-align:right;">
        <?= Html::submitButton('Зберегти', ['class' => 'btn btn-primary']) ?>
       
    </div>

    <?php ActiveForm::end(); ?>
<br><br>
<?php
   if ($model->tree_id!='')
   {
       
       

$items = [
   
    [
        'label'=>/*'<i class="glyphicon glyphicon-usd"></i> */'Фото',
        'content' => $this->render('projects_files', ['id'=>$model->tree_id, 'obj'=>1]),
        'active'=> $tabid==0 ? true : false,
    ],
     [
        'label'=>/*'<i class="glyphicon glyphicon-shopping-cart"></i> */'Файли',
        'content' => $this->render('projects_files', ['id'=>$model->tree_id, 'obj'=>0]),
        'active'=> $tabid==1 ? true : false,
    ],
];

    echo '<div class="nav-tabs-custom" id="filesphoto">'.Tabs::widget([
            'id'=>'cl_tabs',
            'items'=>$items,
            'clientEvents'  => [ 
                    'show.bs.tab'=> "function(e) 
                        { 
                            var idd = $(e.target).parent().index();
                            $.post( 'settab', { id: idd, userid: ".$model->tree_id."} ) .done(function() {
                        });}",
            ],
        ]).'</div>';

   }