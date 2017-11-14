<?php

use kartik\grid\GridView;
use yii\helpers\Html;
use yii\helpers\Url;

if ($type != null) {
                    $parent = \common\models\Tree::findOne($type);
                    $model = new \common\models\ValTree6();
                    $tmModelSearch = "common\\models\\search\\Discount6Search";
                    $msearch = new $tmModelSearch();
                    $dataPro = $msearch->search(Yii::$app->request->queryParams);
                    $dataPro->query->andFilterWhere([
                        'lang' => 'ru',
                        'tree.is_enable' => '1',
                       
                    ])->andFilterWhere(['>', 'tree.lft', $parent->lft])->andFilterWhere(['<', 'tree.rgt', $parent->rgt]);
                } else {
                    $model = new \common\models\ValTree4();
                    $tmModelSearch = "common\\models\\search\\DiscountSearch";
                    $msearch = new $tmModelSearch();
                    $dataPro = $msearch->search(Yii::$app->request->queryParams);
                    $dataPro->query->andFilterWhere([
                        'lang' => 'ru',
                        'isseria' => 'ON',
                        'tree.is_enable' => '1',
                    ]);
                }


?>

<section class="content-header" >
    <h4>
        Скидки        
    </h4>
    <ol class="breadcrumb" style="font-size: 14px;">
        <li><a href="/cabinet"><i class="fa fa-home"></i> Главная</a></li>
        <?php
         if ($type!=null) { ?>
            <li><a href="/cabinet/prop/discount">Скидки</a></li> 
            <li><a><?= $parent->tree_name ?></a></li>  
        <?php } else { ?>
        <li><a>Скидки</a></li>  
        <?php } ?>
    </ol>
</section>

<section class="content">
    <div class="row">
        <div class="col-lg-12">
            <div class="box box-solid" style="border-top: none;">
                <?php
                


                $gridColumns = [
                    [
                        'class' => 'kartik\grid\SerialColumn',
                        'contentOptions' => ['class' => 'kartik-sheet-style'],
                        'width' => '36px',
                        'header' => '',
                        'headerOptions' => ['class' => 'kartik-sheet-style']
                    ],
                    [
                        'attribute' => 'nname',
                        'label' => 'Серия',
                    ],
                    [
                        'attribute' => 'discount1',
                        'label' => 'Предел 1',
                        'class' => 'kartik\grid\EditableColumn',
                        'vAlign' => 'middle',
                        'editableOptions' => [
                            'placement' => kartik\popover\PopoverX::ALIGN_LEFT,
                            /*  'header' => $f->tf_dyspname, */
                            'size' => 'md',
                            'language' => 'uk',
                            'inputType' => \kartik\editable\Editable::INPUT_SPIN,
                            'widgetClass' => 'textInput',
                            'options' => [
                            ],
                        ]],
                    [
                        'attribute' => 'discount2',
                        'label' => 'Предел 2',
                        'class' => 'kartik\grid\EditableColumn',
                        'vAlign' => 'middle',
                        'editableOptions' => [
                            'placement' => kartik\popover\PopoverX::ALIGN_LEFT,
                            /*  'header' => $f->tf_dyspname, */
                            'size' => 'md',
                            'language' => 'uk',
                            'inputType' => \kartik\editable\Editable::INPUT_SPIN,
                            'widgetClass' => 'textInput',
                            'options' => [
                            ],
                        ]
                    ],
                ];

                if ($type == null) {
                    array_push($gridColumns, [
                        'class' => 'kartik\grid\ActionColumn',
                        /*   'dropdown'=>$this->dropdown,
                          'dropdownOptions'=>['class'=>'pull-right'], */
                        'template' => '{role}',
                        'buttons' => [
                                'role' => function($url, $model) {
                                    $url = \yii\helpers\Url::toRoute(['discount?type='.$model->tree_id]);
                                    return Html::a('<span class="glyphicon treeicon-clone glyphicon-duplicate"></span>', $url, [
                                                'title' => 'Детализация',
                                                'data-pjax' => '0', // нужно для отключения для данной ссылки стандартного обработчика pjax. Поверьте, он все портит
                                                'class' => 'grid-clone' // указываем ссылке класс, чтобы потом можно было на него повесить нужный JS-обработчик
                                    ]);
                                },],
                       
                       
                        'headerOptions' => ['class' => 'kartik-sheet-style'],
                    ]);
                }

                echo GridView::widget([
                    'id' => 'kv-grid-'.$type,
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
                        'heading' => 'Пределы скидок',
                    ],
                    'persistResize' => false,
                        /* 'exportConfig'=>$exportConfig, */
                ]);
                ?>
            </div>
        </div>
    </div>
</section>