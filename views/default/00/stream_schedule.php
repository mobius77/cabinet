<?php
use kartik\grid\GridView;
use yii\helpers\Html;
use yii\helpers\Url;
use kartik\form\ActiveForm;
use kartik\builder\Form;
use kartik\date\DatePicker;

$dt = Yii::$app->session->get('dt');

?>

<div class="row">
    <div class="col-lg-4 col-md-6 col-sm-12 ">
        <div class="clients-form">

            <?php  $form = ActiveForm::begin(['id' => 'my-dateform','type'=>ActiveForm::TYPE_INLINE, /*'enableAjaxValidation'=>true, */'enableClientValidation' => false]);

            if (Yii::$app->user->identity->role<10)
                $dtdt = date('Y-m-d', strtotime(date('Y-m-d') . ' +3 day'));
            else
                $dtdt = '2200-01-01';

            echo DatePicker::widget([
                'name' => 'dt',
                'value' => $dt,
                'options' => ['placeholder' => 'Дата ...'],
                'removeButton' => false,
                'pluginOptions' => [
                    'format' => 'yyyy-mm-dd',
                    'todayHighlight' => true,
                    'autoclose' => true,
                    'endDate'=>$dtdt

                ],
                  'pluginEvents' => [
              
                "changeDate" => "function(e) { 
                  
                          $('#my-dateform').submit();
                        
                        }",
               
            ],
            ]);

            ?>

        

            <?php ActiveForm::end(); ?>

        </div>
    </div>
</div>
<br>
<div class="row">
<div class="col-lg-12">
<?php

$gridColumns = [
    [
        'attribute' => 'Время начала',
    ],
    [
        'attribute' => 'Время окончания',
    ],
    [
        'attribute' => 'Стример',
    ],
    [
        'attribute' => 'Название',
    ],
    [
        'attribute' => 'Призовой фонд',
    ],


[
    'class'=>'kartik\grid\ActionColumn',
    'dropdown' => false,
    'header' => '',
    'vAlign' => 'middle',
    'hAlign'=>'right',
    'template' => '{create} {update} {clone} {delete}',
    'buttons' => [
        'create' => function($url, $model) use($dt) {
            $url = \yii\helpers\Url::toRoute(['site/create-schedule', 'dt' => $dt, 'time'=>$model['id']]);
            $hide_cl = '';
            if (($model['templ_id']!='')||($model['order_state']===0)) {
                $hide_cl = 'my-hide';
            }
            
            return Html::a('Занять', $url, [
                'title' => 'Занять',
                'data-pjax' => '0', // нужно для отключения для данной ссылки стандартного обработчика pjax. Поверьте, он все портит
                'class' => 'grid-action btn btn-success '.$hide_cl // указываем ссылке класс, чтобы потом можно было на него повесить нужный JS-обработчик
            ]);
        },
        'update' => function($url, $model) use($dt) {
            $url = \yii\helpers\Url::toRoute(['site/update-schedule', 'id'=>$model['order_id']]);
            $hide_cl = '';
            if (((($model['order_state']===0)||($model['user_id']!=Yii::$app->user->id))&&(Yii::$app->user->identity->role<10))||($model['order_id']=='')) {
                $hide_cl = 'my-hide';
            }

            return Html::a('Изменить', $url, [
                'title' => 'Изменить',
                'data-pjax' => '0', // нужно для отключения для данной ссылки стандартного обработчика pjax. Поверьте, он все портит
                'class' => 'grid-action btn btn-info '.$hide_cl // указываем ссылке класс, чтобы потом можно было на него повесить нужный JS-обработчик
            ]);
        },
             'clone' => function($url, $model) use($dt) {
                 $url = \yii\helpers\Url::toRoute(['/site/create-schedule', 'dt' => $dt, 'time' => $model['id'], 'dop'=>1 ]);
                 $hide_cl = '';
                 if (($model['templ_id']=='')||(($model['Время окончания']-$model['Время начала'])==2)||($model['order_state']===0)/*||($model['user_id']==Yii::$app->user->id)*/) {
                     $hide_cl = 'my-hide';
                 }

                 return Html::a('Дополнить', $url, [
                             'title' => 'Дополнить',
                            /* 'disabled'=>true,*/
                             'data-pjax' => '0', // нужно для отключения для данной ссылки стандартного обработчика pjax. Поверьте, он все портит
                             'class' => 'grid-clone btn btn-primary '.$hide_cl  // указываем ссылке класс, чтобы потом можно было на него повесить нужный JS-обработчик
                 ]);
             },
        'delete' => function($url, $model) use($dt) {
            $url = \yii\helpers\Url::toRoute(['/site/delete-schedule', 'order_id' => $model['order_id']]);
            $hide_cl = '';
            if (($model['user_id']!=Yii::$app->user->id)&&(Yii::$app->user->identity->role<10)||($model['order_state']===0)) {
                $hide_cl = 'my-hide';
            }

            return Html::a('Отменить', $url, [
                'title' => 'Отменить',
                'data-pjax' => '0', // нужно для отключения для данной ссылки стандартного обработчика pjax. Поверьте, он все портит
                'class' => 'grid-delete btn btn-danger '.$hide_cl // указываем ссылке класс, чтобы потом можно было на него повесить нужный JS-обработчик
            ]);
        },
    ],
],
];


echo GridView::widget([
    'id' => 'kv-grid-demo',
    'dataProvider'=>$dataPro,
    'columns'=>$gridColumns,
    'containerOptions'=>['style'=>'overflow: auto'], // only set when $responsive = false
    'headerRowOptions'=>['class'=>'kartik-sheet-style'],
    'filterRowOptions'=>['class'=>'kartik-sheet-style'],
    'pjax'=>true, // pjax is set to always true for this demo
    // set your toolbar
    'toolbar'=> [

    ],
        // set export properties
        'export'=>[
    'fontAwesome'=>true
],
        // parameters from the demo form

    'rowOptions' => function ($model, $key, $index, $column) {
        $bcolor='mm';
        if ($model['order_state']===0) {$bcolor = 'warn_balanse';}
        return [
            'class' =>  $bcolor,
        ];
    },

        'bordered'=>true,
        'striped'=>true,
        'condensed'=>false,
        'responsive'=>true,
        'hover'=>true,
        'showPageSummary'=>false,
        'panel'=>[
    'type'=>GridView::TYPE_PRIMARY,
    'heading'=>'Расписание',
],
        'persistResize'=>false,
       /* 'exportConfig'=>$exportConfig,*/
    ]);

?>
    </div>
</div>