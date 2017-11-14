<?php

use kartik\grid\GridView;
use yii\helpers\Html;
use yii\helpers\Url;
use frontend\modules\cabinet\models\UserGroups;
?>

<section class="content-header" >
    <h4>
        Все пользователи        
    </h4>
    <ol class="breadcrumb" style="font-size: 14px;">
        <li><a href="/cabinet"><i class="fa fa-home"></i> Главная</a></li>
        <li><a>Все пользователи</a></li>        
    </ol>
</section>

<section class="content">
    <div class="row">
        <div class="col-lg-12">
            <div class="box box-solid" style="border-top: none;">
                <?php
                $tmModelSearch = "frontend\\modules\\cabinet\\models\\search\\UserSearch";

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
                        'attribute' => 'username',
                        'label' => 'Логин',
                    ],
                        [
                        'attribute' => 'user_firstname',
                        'label' => 'Имя пользователя',
                    ],
                    [
                        'attribute' => 'user_type',
                        'format' => 'raw',
                        'label' => 'Тип пользователя',
                        'value' => function ($model, $key, $index, $widget) use($field) {
                            if (Yii::$app->authManager->getAssignment('user', Yii::$app->user->getId()))  {
                                if ($model->user_type > 0) {
                                    return 'Юр. лицо (клиент)';
                                } else {
                                    return 'Физ. лицо (клиент)';
                                }
                            }
                            return 'Сотрудник';
                        }
                    ],
                        [
                        'attribute' => 'user_firstname',
                        'label' => 'Имя пользователя',
                    ],
                        [
                        'attribute' => 'gender',
                        'format' => 'raw',
                        'label' => 'Группа',
                        'value' => function ($model, $key, $index, $widget) use($field) {

                            $u_group = UserGroups::find()->where(['ug_id' => $model->gender])->one();
                            return $u_group->ug_name;
                        }
                    ],
                        [
                        'attribute' => 'gender',
                        'format' => 'raw',
                        'label' => 'Скидка',
                        'value' => function ($model, $key, $index, $widget) use($field) {

                            $u_group = UserGroups::find()->where(['ug_id' => $model->gender])->one();
                            return $u_group->ug_skidka;
                        }
                    ],
                        [
                        'attribute' => 'status',
                        'format' => 'raw',
                        'label' => 'Статус',
                        'value' => function ($model, $key, $index, $widget) use($field) {

                            if ($model->status == 10) {
                                return Html::tag('span', '<span id="stat' . $model->id . '" class="onholdicon glyphicon glyphicon-user text-success"></span>', [
                                            'title' => 'Активен',
                                            'data-toggle' => 'tooltip',
                                            'style' => 'text-decoration: underline: cursor:pointer;']);
                            } else {
                                return Html::tag('span', '<span id="stat' . $model->id . '" class="onholdicon glyphicon glyphicon-user text-danger"></span>', [
                                            'title' => 'Заблокирован',
                                            'data-toggle' => 'tooltip',
                                            'style' => 'text-decoration: underline: cursor:pointer;']);
                            }
                        }
                    ],
                        [
                        'class' => 'kartik\grid\ActionColumn',
                        /*   'dropdown'=>$this->dropdown,
                          'dropdownOptions'=>['class'=>'pull-right'], */
                        'template' => '{role} {update} {delete} ',
                        'buttons' => [
                                'role' => function($url, $model) {
                                    $url = \yii\helpers\Url::toRoute(['/permit/user/view/'.$model->id]);
                                    return Html::a('<span class="glyphicon treeicon-clone glyphicon-duplicate"></span>', $url, [
                                                'title' => 'Присвоить роли',
                                                'data-pjax' => '0', // нужно для отключения для данной ссылки стандартного обработчика pjax. Поверьте, он все портит
                                                'class' => 'grid-clone' // указываем ссылке класс, чтобы потом можно было на него повесить нужный JS-обработчик
                                    ]);
                                },],
                        'urlCreator' => function($action, $model, $key, $index) {
                            return Url::toRoute([$action . '-user', 'id' => $model->id]);
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
                            ['content' => ''
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
                        'heading' => 'Пользователи',
                    ],
                    'persistResize' => false,
                        /* 'exportConfig'=>$exportConfig, */
                ]);
                ?>
            </div>
        </div>
    </div>
</section>