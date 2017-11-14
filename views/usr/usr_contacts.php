<?php

use kartik\grid\GridView;
use yii\helpers\Html;
use yii\helpers\Url;
?>

<?php if ($b_crumb != 'adm') { ?> 
    <section class="content-header" style="margin-bottom: 15px;">
        <h4>
            Контакты     
        </h4>
        <ol class="breadcrumb" style="font-size: 14px;">
            <li><a href="/cabinet"><i class="fa fa-home"></i> Главная</a></li>
            <li class="active">Контакты</li>   
        </ol>

    </section>
    <section class="content">
        <div class="box box-primary" style="border-top: none;">

        <?php } ?>

        <?php
        $gridColumns = [
            [
                'class' => 'kartik\grid\SerialColumn',
                'contentOptions' => ['class' => 'kartik-sheet-style'],
                'width' => '36px',
                'header' => '',
                'headerOptions' => ['class' => 'kartik-sheet-style']
            ],
            /*
              [
              'attribute' => 'c_name',
              'label' => 'Имя',
              ],
             */
                                
            [
                'attribute' => 'c_famil',
                'label' => 'Фамилия',
            ],
            
            [
                'attribute' => 'c_name',
                'label' => 'Имя',
            ],
                    
            [
                'attribute' => 'c_otch',
                'label' => 'Отчество',
            ],
                        
            [
                'attribute' => 'c_email',
                'label' => 'E-mail',
            ],
            
            [
                'attribute' => 'c_phone',
                'label' => 'Телефон',
            ],
                    
            [
                'attribute' => 'c_post',
                'label' => 'Должность',

            ],        
            
            [
                'attribute' => 'c_type',
                'format' => 'raw',
                'label' => 'Тип',
                'value' => function ($model, $key, $index, $widget) use($field) {
                    if ($model->c_type == 1) {
                        $disp_type = 'Юр. лицо';
                    } else {
                        $disp_type = 'Физ. лицо';
                    }
                    if ($model->a_flag == 1) {
                        return '<b>' . $disp_type . '</b>';
                    } else {
                        return $disp_type;
                    }
                }
            ],         
            
            [
                'attribute' => 'c_edr',
                'format' => 'raw',
                'label' => 'ЕГРПОУ',
            ], 
                    
            [
                'attribute' => 'c_note',
                'label' => 'Примечания',
            ],        
                    
            [
                'class' => 'kartik\grid\ActionColumn',
                /*   'dropdown'=>$this->dropdown,
                  'dropdownOptions'=>['class'=>'pull-right'], */
                'template' => '{update} {delete}',
                'urlCreator' => function($action, $model, $key, $index) {
                    return Url::toRoute([$action . '-usr-contacts', 'c_id' => $model->c_id]);
                },
                'updateOptions' => ['title' => 'Изменить', 'data-toggle' => 'tooltip'],
                'deleteOptions' => ['title' => 'Удалить', 'data-toggle' => 'tooltip'],
                'headerOptions' => ['class' => 'kartik-sheet-style'],
            ],
            [
                'class' => 'kartik\grid\ActionColumn',
                'header' => 'Адреса',
                'template' => '{view}',
                'urlCreator' => function($action, $model, $key, $index) {
                    return Url::toRoute(['usr-adress', 'c_id' => $model->c_id, 'u_id' => $u_id]);
                },
                'viewOptions' => ['title' => 'Просмотр адресов', 'data-toggle' => 'tooltip'],
                'headerOptions' => ['class' => 'kartik-sheet-style'],
            ],
        ];



        echo GridView::widget([
            'id' => 'usr_cont_grid',
            'dataProvider' => $dataProvider,
            'filterModel' => null,
            'columns' => $gridColumns,
            'containerOptions' => ['style' => 'overflow: auto'], // only set when $responsive = false
            'headerRowOptions' => ['class' => 'kartik-sheet-style'],
            'filterRowOptions' => ['class' => 'kartik-sheet-style'],
            'pjax' => true, // pjax is set to always true for this demo
            // set your toolbar
            'toolbar' => [
                ['content' =>
                    Html::a('<i class="glyphicon glyphicon-plus"></i>', ['add-usr-contact', 'u_id' => $u_id], ['data-pjax' => 0, 'class' => 'btn btn-default', 'title' => Yii::t('kvgrid', 'Добавить контакт')])
                ],
                '{export}',
                '{toggleData}',
            ],
            // set export properties
            'export' => [
                'fontAwesome' => true
            ],
            // parameters from the demo form
            'bordered' => true,
            'striped' => true,
            'condensed' => true,
            'responsive' => true,
            'hover' => true,
            'showPageSummary' => false,
            'panel' => [
                'type' => GridView::TYPE_PRIMARY,
                'heading' => 'Контакты',
            ],
            'persistResize' => false,
                /* 'exportConfig'=>$exportConfig, */
        ]);

        if ($b_crumb != 'adm') {
            ?> 
        </div>
    </section> 
<?php } ?>
    

