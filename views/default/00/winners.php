<?php
use kartik\grid\GridView;
use yii\helpers\Html;
use yii\helpers\Url;
use kartik\form\ActiveForm;
use kartik\builder\Form;
use kartik\date\DatePicker;
use common\models\Winner;
use common\models\search\WinnerSearch;

if (!isset($dts)) $dts = date('Y-m-01');
if (!isset($dte)) $dte = date('Y-m-t');

?>

<!--<div class="row">
    <div class="col-lg-4 col-md-6 col-sm-12 ">

    </div>
</div>
<br> -->
<div class="row">
<div class="col-lg-12">
<?php


$model = new Winner();
$msearch = new WinnerSearch();
$dataPro = $msearch->search(Yii::$app->request->queryParams);

$dataPro->query->andFilterWhere([
    ]);



$gridColumns = [
    [
        'attribute' => 'stream_id',
        'label' => 'ID стрима',
    ],
        [
        'attribute' => 'date_win',
        'filterType'=>'kartik\daterange\DateRangePicker',
         'filterWidgetOptions'=>[
             'pluginOptions'=>[
             'locale'=>['format' => 'YYYY-MM-DD','separator' => ' - '],
             ],
             ],
            
    ],
     [
        'attribute' => 'streamernick',
    ],   
    [
        'attribute' => 'winnernick',
    ],
    [
        'attribute' => 'sum_win',
    ],
    [
        'attribute' => 'paid_date_win',
        'format' => ['date', 'php:Y-m-d'],
        
    ],
    

];


echo GridView::widget([
    'id' => 'kv-grid-demo',
    'dataProvider'=>$dataPro,
    'filterModel'=>$msearch,
    'columns'=>$gridColumns,
    'containerOptions'=>['style'=>'overflow: auto'], // only set when $responsive = false
    'headerRowOptions'=>['class'=>'kartik-sheet-style'],
    'filterRowOptions'=>['class'=>'kartik-sheet-style'],
    'pjax'=>true, // pjax is set to always true for this demo
    // set your toolbar
    'toolbar'=> [
        '{export}',
        '{toggleData}',
    ],
        // set export properties
        'export'=>[
    'fontAwesome'=>true
],
        // parameters from the demo form

    'rowOptions' => function ($model, $key, $index, $column) {

    },

        'bordered'=>true,
        'striped'=>true,
        'condensed'=>false,
        'responsive'=>true,
        'hover'=>true,
        'showPageSummary'=>false,
        'panel'=>[
    'type'=>GridView::TYPE_PRIMARY,
    'heading'=>'История начисления призов',
],
        'persistResize'=>false,
      
    ]);

?>
    </div>
</div>