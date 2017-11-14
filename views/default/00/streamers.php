<?php
use kartik\grid\GridView;
use yii\helpers\Html;
use yii\helpers\Url;
use kartik\form\ActiveForm;
use kartik\builder\Form;
use kartik\date\DatePicker;
use common\models\Order;
use common\models\search\OrderSearch;

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


$model = new Order();
$msearch = new OrderSearch();
$dataPro = $msearch->search(Yii::$app->request->queryParams);

$dataPro->query->andFilterWhere(['order_state'=>1])->
        andFilterWhere(['order_status'=>2]);



$gridColumns = [
      [
        'attribute' => 'order_id',
         'format' => 'raw', 
          'value' => function ($model, $key, $index, $widget) {
                        return "<a href = '/admin/site/get-winners?WinnerSearch%5Bstream_id%5D=".$model->order_id."'>".$model->order_id."</a>";
                    },
    ],
        [
        'attribute' => 'order_date',
        'filterType'=>'kartik\daterange\DateRangePicker',
         'filterWidgetOptions'=>[
             'pluginOptions'=>[
             'locale'=>['format' => 'YYYY-MM-DD','separator' => ' - '],
             ],
             ],
            
    ],
        [
        'attribute' => 'order_time_start',
        'header' => 'Время',
        'width' => '70px',
        'format' => 'raw',
        'hAlign' => 'center',
        'value' => function ($model, $key, $index, $widget) {
                        return $model->order_time_start . '-' . $model->order_time_end;
                    },
    ],
     [
        'attribute' => 'streamernick',
         'label' => 'Стример',
    ],   
    [
        'attribute' => 'templ.templ_anons',
    ],

    [
        'attribute' => 'sumwin',
        'label' => 'ПФ',
        'pageSummary'=>true,
    ],                        
    [
       
        'header' => 'ЗП',
        'width' => '70px',
        'format' => 'raw',
        'hAlign' => 'center',
        'value' => function ($model, $key, $index, $widget) {
                        return ($model->order_time_end-$model->order_time_start)*500;
                    },
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
    'heading'=>'История стримов',
],
        'persistResize'=>false,
      
    ]);

?>
    </div>
</div>