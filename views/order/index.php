<?php

use yii\helpers\Html;
use kartik\dynagrid\DynaGrid;
use kartik\grid\GridView;
use kartik\icons\Icon;
use \kartik\widgets\Select2;
use frontend\modules\cabinet\models\OrdStatus;


$stats = OrdStatus::find()->all();

?>

<style type="text/css">
 <?php
 foreach ($stats as $stat) { ?>
.color_<?= $stat->s_id ?> {
    background-color: <?= $stat->s_color ?> !important;
}
 <?php } ?>
</style>

<section class="content-header" >
    <h1>
        Заказы
    </h1>
    <ol class="breadcrumb" style="font-size: 14px;">
        <li><a href="/cabinet"><i class="fa fa-home"></i> Главная</a></li>         
        <li class="active">Заказы</li>
    </ol>
</section>

<section class="content1">
<?php
$tree_id = Yii::$app->request->get('tree_id');
/*
$statuses = [8 => 'Pre-Ordered',
    2 => 'Paid',
    3 => 'Shipped',
    4 => 'Returning',
    5 => 'Exchanging',
    6 => 'Exchanged',
    7 => 'Refund Issued ',
    10 => 'Canceled'
];
*/

$tmModel = "common\models\MOrders";
$tmModelSearch = "common\models\search\MOrdersSearch";
$tm_id = '-orders';
$model = new $tmModel();
$msearch = new $tmModelSearch();

$dataPro = $msearch->search(Yii::$app->request->queryParams);


if (Yii::$app->authManager->getAssignment('user', Yii::$app->user->getId()))
    $dataPro->query->andFilterWhere(['=', 'user_id', Yii::$app->user->id]);
else
    $dataPro->query->andFilterWhere(['<>', 'order_status', '0']);

$columns = [

    /*  [
      'class' => 'kartik\grid\SerialColumn',
      'contentOptions' => ['class' => 'kartik-sheet-style'],
      'width' => '36px',
      'header' => '',
      'headerOptions' => ['class' => 'kartik-sheet-style']
      ], */
    [
        'attribute' => 'order_uuid',
         'label' => '#',
        'width' => '90px',
    ],
    
     [
    'class' => 'kartik\grid\ActionColumn',
    'dropdown' => false,
    'header' => '',
    'width' => '50px',
    'vAlign' => 'middle',
    'template' => '{edit}',
    'buttons' => [
        'edit' => function($url, $model) {
            $url = \yii\helpers\Url::toRoute(['edit-order', 'order_id' => $model->order_id]);
            return Html::a('<span class="glyphicon treeicon-edit glyphicon-info-sign"></span>', $url, [
                        'title' => 'Редактировать',
                        'data-pjax' => '0', // нужно для отключения для данной ссылки стандартного обработчика pjax. Поверьте, он все портит
                        'class' => 'grid-action' // указываем ссылке класс, чтобы потом можно было на него повесить нужный JS-обработчик
            ]);
        },
            ],
        ],
    
            [
                'attribute' => 'orderStatus.s_name',
                'width' => '130px',
                'label' => 'Статус',
                'contentOptions' => ['style' => 'min-width: 130px;'],
                'filter' => false,
            ],
            [
             
                'label' => '# декларации',
                'attribute' => 'order_decnum',
                'width' => '130px',
                'contentOptions' => ['style' => 'min-width: 130px;'],
                'filter' => false,
            ],
            [
                'attribute' => 'order_date',
                 'label' => 'Дата',
                'width' => '220px',
                'contentOptions' => ['style' => 'min-width: 120px;'],
                   'filterType' => '\kartik\widgets\DatePicker', 
                'filter' => false,
                'format' => 'raw',
               'filterWidgetOptions' => [
              'pluginOptions' => ['format' => 'yyyy-mm-dd', 'todayHighlight' => true, 'autoclose' => true,]
              ], 
            ],
            [
                'attribute' => 'username',
                 'label' => 'Клиент',
            ],
            [
                'attribute' => 'uContact.c_name',
                'label' => 'Контакт',
                'width' => '250px',
            ],
            [
                'attribute' => 'sumitems',
                'width' => '150px',
                'label' => 'Сумма',
                'format' => ['decimal', 2],
                'hAlign' => 'right',
            ],
        ];



        echo DynaGrid::widget([
            'enableMultiSort' => false,
            'gridOptions' => [
                'panel' => ['heading' => 'Orders', 'type' => GridView::TYPE_PRIMARY,],
                'hover' => true,
                'condensed' => true,
                'pjax' => true,
                'responsive'=>true,
                'responsiveWrap'=>false,
                'toolbar' => [
                    ['content' => ' {toggleData}'
                    ],
                ],
                'pjaxSettings' => [
                ],
                'dataProvider' => $dataPro,
                'filterModel' => $msearch,
                'rowOptions' => function ($model, $key, $index, $column) {
            $row_color = '';
           /* $co = common\models\MOrderReq::find()->where('order_id ='.$model->order_id.' AND item_status in (3,4)')->count();
            switch ($model->order_status) {
                case "10": case "7":
                    $row_color = 'grayrow graybg';
                    break;
                case "2":
                    $row_color = 'greenebg';
                    break;
                case "8":
                    $row_color = 'yelbg';
                    break;
                case "5":
                    $row_color = 'pinkebg';
                    break;
                case "4":
                    $row_color = 'redebg';
                    break;
                case "6": case"3":
                    $row_color = 'bluebg';
                    break;                
            }
            if ($co>0) {
               $row_color.=' neg_balanse '; 
            }*/
            
             $row_color = 'color_'.$model->order_status;
            
            return ['class' => $row_color . " items[]_" . $model->order_id,];
        },
                'options' => ['id' => 'grid' . $tm_id, 'class' => '']
            ],
            'columns' => $columns,

            'options' => [
                'id' => 'project-grid' . $tm_id, 
                'class' => '',
                ]
        ]);
        ?>
</section>