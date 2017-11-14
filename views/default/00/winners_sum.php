<?php
use kartik\grid\GridView;
use yii\helpers\Html;
use yii\helpers\Url;
use kartik\form\ActiveForm;
use kartik\builder\Form;
use kartik\date\DatePicker;
use common\models\Winner;
use common\models\search\WinnerSearchSum;

if (!isset($dts)) $dts = date('Y-m-01');
if (!isset($dte)) $dte = date('Y-m-t');

?>

<div class="row">
    <div class="col-lg-4 col-md-6 col-sm-12 ">
    <div class="clients-form">

            <?php  $form = ActiveForm::begin(['action'=>'/admin/site/close-period', 'type'=>ActiveForm::TYPE_INLINE, /*'enableAjaxValidation'=>true, */'enableClientValidation' => false]);


            echo DatePicker::widget([
                'name' => 'dts',
                'value' => $dts,
                'options' => ['placeholder' => 'Дата ...'],
                'removeButton' => false,
                'pluginOptions' => [
                    'format' => 'yyyy-mm-dd',
                    'todayHighlight' => true,
                    'autoclose' => true,

                ]
            ]);

            ?>

            <div class="form-group">
                <?= Html::submitButton('Закрыть период', ['class' => 'btn btn-success']) ?>
            </div>

            <?php ActiveForm::end(); ?>

        </div>
    </div>
</div>
<br> 
<div class="row">
<div class="col-lg-12">
<?php


$model = new Winner();
$msearch = new WinnerSearchSum();
$dataPro = $msearch->search(Yii::$app->request->queryParams);

$dataPro->query->andFilterWhere([
    ]);



$gridColumns = [
        [
        'attribute' => 'date_win',
            
        'filterType'=>'kartik\date\DatePicker',
         'filterWidgetOptions'=>[

             'pluginOptions'=>[
                'format' => 'yyyy-mm-dd'],
                
             
             ],
            
    ],
   
    [
        'attribute' => 'winnernick',
    ],
    [
        'attribute' => 'sum_win',
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
    'heading'=>'Сводная ведомость по победителям',
],
        'persistResize'=>false,
      
    ]);

?>
    </div>
</div>