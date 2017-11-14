<?php
use kartik\grid\GridView;
use yii\helpers\Html;
use yii\helpers\Url;

?>

<div class="row">
<div class="col-lg-12">
<?php

$tmModelSearch = "common\\models\\search\\TemplateSearch";

$msearch = new $tmModelSearch();

$dataPro = $msearch->search(Yii::$app->request->queryParams);

$dataPro->query->andFilterWhere([
    'user_id' => Yii::$app->user->id,
]);

$colorPluginOptions =  [
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
        'class'=>'kartik\grid\SerialColumn',
        'contentOptions'=>['class'=>'kartik-sheet-style'],
        'width'=>'36px',
        'header'=>'',
        'headerOptions'=>['class'=>'kartik-sheet-style']
    ],
    [
        'attribute' => 'templ_name',
    ],
    [
        'attribute' => 'templ_anons',
    ],
    [
        'attribute' => 'templ_descr',
    ],
    [
        'attribute' => 'templ_cond',
    ],

[
    'class'=>'kartik\grid\ActionColumn',
 /*   'dropdown'=>$this->dropdown,
    'dropdownOptions'=>['class'=>'pull-right'],*/
    'urlCreator'=>function($action, $model, $key, $index) {
        if ($action === 'yandexCampaign') {
            return Url::toRoute(['add-yandex-campaign', 'id' => $model->templ_id]);
        } else {
            return Url::toRoute([$action.'-templ', 'id' => $model->templ_id]);
        }
    },
    'updateOptions'=>['title'=>'Редактировать', 'data-toggle'=>'tooltip'],
    'deleteOptions'=>['title'=>'Удалить шаблон', 'data-toggle'=>'tooltip'],
    'headerOptions'=>['class'=>'kartik-sheet-style'],
],
];



echo GridView::widget([
    'id' => 'kv-grid-demo',
    'dataProvider'=>$dataPro,
    'filterModel'=>null,
    'columns'=>$gridColumns,
    'containerOptions'=>['style'=>'overflow: auto'], // only set when $responsive = false
    'headerRowOptions'=>['class'=>'kartik-sheet-style'],
    'filterRowOptions'=>['class'=>'kartik-sheet-style'],
    'pjax'=>true, // pjax is set to always true for this demo
    // set your toolbar
    'toolbar'=> [
        ['content'=>
            Html::a('<i class="glyphicon glyphicon-plus"></i> Добавить шаблон', ['create-templ'], ['data-pjax'=>0, 'class'=>'btn btn-success', 'title'=>Yii::t('kvgrid', 'Reset Grid')]) . ' '.
            Html::a('<i class="glyphicon glyphicon-repeat"></i>', ['grid-demo'], ['data-pjax'=>0, 'class'=>'btn btn-default', 'title'=>Yii::t('kvgrid', 'Reset Grid')])
        ],
        '{export}',
        '{toggleData}',
    ],
        // set export properties
        'export'=>[
    'fontAwesome'=>true
],
        // parameters from the demo form
        'bordered'=>true,
        'striped'=>true,
        'condensed'=>true,
        'responsive'=>true,
        'hover'=>true,
        'showPageSummary'=>false,
        'panel'=>[
    'type'=>GridView::TYPE_PRIMARY,
    'heading'=>'Шаблоны',
],
        'persistResize'=>false,
       /* 'exportConfig'=>$exportConfig,*/
    ]);

?>
    </div>
</div>