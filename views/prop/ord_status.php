<?php

use kartik\grid\GridView;
use yii\helpers\Html;
use yii\helpers\Url;
?>

<section class="content-header" >
    <h4>
        Статусы заказов        
    </h4>
    <ol class="breadcrumb" style="font-size: 14px;">
        <li><a href="/cabinet"><i class="fa fa-home"></i> Главная</a></li>
        <li><a>Статусы заказов</a></li>        
    </ol>
</section>

<section class="content">
    <div class="row">
        <div class="col-lg-12">
            <div class="box box-solid" style="border-top: none;">
                <?php
                $tmModelSearch = "frontend\\modules\\cabinet\\models\\search\\OrdStatusSearch";

                $msearch = new $tmModelSearch();

                $dataPro = $msearch->search(Yii::$app->request->queryParams);

                /* $dataPro->query->andFilterWhere([
                  'user_id' => Yii::$app->user->id,
                  ]); */



                $colorPluginOptions = [
                    'showPalette' => true,
                    'showPaletteOnly' => true,
                    'showSelectionPalette' => true,
                    'showAlpha' => false,
                    'allowEmpty' => false,
                    'preferredFormat' => 'name',
                    'palette' => [
                            [
                            "white", "black", "grey", "silver", "gold", "brown",
                        ],
                            [
                            "red", "orange", "yellow", "indigo", "maroon", "pink"
                        ],
                            [
                            "blue", "green", "violet", "cyan", "magenta", "purple",
                        ],
                    ]
                ];
                $gridColumns = [
                        [
                        'class' => 'kartik\grid\SerialColumn',
                        'contentOptions' => ['class' => 'kartik-sheet-style'],
                        'width' => '36px',
                        'header' => '',
                        'headerOptions' => ['class' => 'kartik-sheet-style']
                    ],
                        [
                        'attribute' => 's_name',
                        'label' => 'Наименование',
                    ],
                        [
                        'attribute' => 'status',
                        'format' => 'raw',
                        'label' => 'Цвет',
                        'value' => function ($model, $key, $index, $widget) use($field) {
                           
                        return Html::tag('i', '', [
                                            'class' => 'fa fa-square',
                                            'style' => [
                                                'text-decoration' => 'none',
                                                'cursor' => 'pointer',
                                                'color' => $model->s_color
                                            ]
                            ]);
                           
                        }
                    ],
                        [
                        'class' => 'kartik\grid\ActionColumn',
                        /*   'dropdown'=>$this->dropdown,
                          'dropdownOptions'=>['class'=>'pull-right'], */
                        'template' => '{update} {delete}',
                        'urlCreator' => function($action, $model, $key, $index) {
                            return Url::toRoute([$action . '-ord-status', 's_id' => $model->s_id]);
                        },
                        'updateOptions' => ['title' => 'Редактировать', 'data-toggle' => 'tooltip'],
                        'deleteOptions' => ['title' => 'Удалить', 'data-toggle' => 'tooltip'],
                        'headerOptions' => ['class' => 'kartik-sheet-style'],
                    ],
                        /*
                          [
                          'class'=>'kartik\grid\ActionColumn',
                          'header'=>'Контакты',
                          'template' => '{view}',
                          'urlCreator'=>function($action, $model, $key, $index) {
                          return Url::toRoute(['usr-contacts', 'u_id' => $model->id]);
                          },
                          'viewOptions'=>['title'=>'Просмотр контактов', 'data-toggle'=>'tooltip'],
                          'headerOptions'=>['class'=>'kartik-sheet-style'],
                          ],

                          [
                          'class'=>'kartik\grid\ActionColumn',
                          'header'=>'Адреса',
                          'template' => '{view}',
                          'urlCreator'=>function($action, $model, $key, $index) {
                          return Url::toRoute(['usr-adress', 'u_id' => $model->id]);
                          },
                          'viewOptions'=>['title'=>'Просмотр адресов', 'data-toggle'=>'tooltip'],
                          'headerOptions'=>['class'=>'kartik-sheet-style'],
                          ],
                         */
                ];



                echo GridView::widget([
                    'id' => 'kv-grid-demo',
                    'dataProvider' => $dataPro,
                    'filterModel' => null,
                    'columns' => $gridColumns,
                    'containerOptions' => ['style' => 'overflow: auto'], // only set when $responsive = false
                    'headerRowOptions' => ['class' => 'kartik-sheet-style'],
                    'filterRowOptions' => ['class' => 'kartik-sheet-style'],
                    'pjax' => true, // pjax is set to always true for this demo
                    // set your toolbar
                    'toolbar' => [
                            ['content' => Html::a('<i class="glyphicon glyphicon-plus"></i>', ['add-ord-status'], ['data-pjax' => 0, 'class' => 'btn btn-default', 'title' => Yii::t('kvgrid', 'Добавить статус')])
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
                        'heading' => 'Статусы заказов',
                    ],
                    'persistResize' => false,
                        /* 'exportConfig'=>$exportConfig, */
                ]);
                ?>
            </div>
        </div>
    </div>
</section>
