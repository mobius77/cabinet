<?php
use kartik\grid\GridView;
use yii\helpers\Html;
use yii\helpers\Url;
use kartik\form\ActiveForm;
use kartik\builder\Form;
use kartik\date\DatePicker;
use common\models\Order;
use common\models\search\OrderSearchSum;

if (!isset($dts)) $dts = date('Y-m-01');
if (!isset($dte)) $dte = date('Y-m-t');

?>

<div class="row">
<div class="col-lg-12">
<?php


$model = new Order();
$msearch = new OrderSearchSum();
$dataPro = $msearch->search(Yii::$app->request->queryParams);

$dataPro->query->andFilterWhere([
    ]);



$gridColumns = [
       [
        'attribute' => 'order_date',
        'label' => 'Дата последнего стрима (за период)',
        'filterType'=>'kartik\daterange\DateRangePicker',
         'filterWidgetOptions'=>[
             'pluginOptions'=>[
             'locale'=>['format' => 'YYYY-MM-DD','separator' => ' - '],
             ],
             ],
            
    ],
   
    [
        'attribute' => 'streamernick',
         'label' => 'Стример',
    ],
    [
        'attribute' => 'order_time_end',
        'label' => 'ЗП',
        'pageSummary'=>true,
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
        'showPageSummary'=>true,
        'panel'=>[
    'type'=>GridView::TYPE_PRIMARY,
    'heading'=>'Сводная ведомость по стримерам',
],
        'persistResize'=>false,
      
    ]);

?>
    </div>
</div>