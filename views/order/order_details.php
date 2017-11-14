<?php

use yii\helpers\Html;
use kartik\dynagrid\DynaGrid;
use kartik\grid\GridView;
use kartik\icons\Icon;
use \kartik\widgets\Select2;
use kartik\widgets\ActiveForm;
use kartik\builder\Form;
use frontend\modules\cabinet\models\UserContacts;
use frontend\modules\cabinet\models\UserAdress;
use yii\helpers\ArrayHelper;
use kartik\depdrop\DepDrop;
use yii\helpers\Url;
use frontend\modules\cabinet\models\OrdStatus;


/*  $order_id = $order->order_id; */
/* $delivery = ['Standard delivery - 8.99$ (US only)', 'Rush Delivery - 14.99$ (US only)', 'International delivery - 25.99$'];
  $statuses = [
  1 => 'Normal',
  2 => 'Exchange',
  ];
  $statuses_ret = [
  3 => 'Return',
  4 => 'Exchange',
  5 => 'Returned',
  6 => 'Exchanged',
  ]; */

$o_statuses = ArrayHelper::map(OrdStatus::find()->asArray()->all(), 's_id', 's_name');
?>



<!-- Main content -->
<section class="invoice1">
    <!-- title row -->
    <div class="row">
        <div class="col-xs-12">
            <h2 class="page-header">
                <i class="fa fa-globe"></i> <a style="color: #006600;" href="/cabinet/order/index">Заказы</a>  / Заказ #<?= $order->order_uuid ?>
                <small class="pull-right">Дата: <?= $order->order_date ?></small>
            </h2>
        </div>
        <!-- /.col -->
    </div>





    <div>

        <div class="row margin-left-sm">

         <?php
             if (Yii::$app->authManager->getAssignment('admin', Yii::$app->user->getId()) || Yii::$app->authManager->getAssignment('manager', Yii::$app->user->getId())) { ?>
            <div class="col-lg-12 table-responsive">

                <div class="box box-widget ">
                    <div class="box-header with-border">
                        <div class="user-block1">
                            <h4><span class="username">Оформил: <a href="#"><?= $order->user->user_firstname ?></a></span>
                            <span class="description">(<?= $order->user->user_pasport ?>)</span></h4>
                        </div>
                        <!-- /.user-block -->
                        <div class="box-tools">
                            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                        </div>
                        <!-- /.box-tools -->
                    </div>
                    <!-- /.box-header -->
                    <div class="box-body">
                        <table  style="margin-bottom: 20px; width:100%; border: solid 1px #eee; " cellspacing=5 class="table-striped dataTable table-bordered table" >
                            <tr>
                                <td><div style="width:220px; float:left;"><strong>E-mail:</strong></div> <?= $order->user->username ?></td>
                            </tr>
                            <tr>
                                <td><div style="width:220px; float:left;"><strong>Полное название:</strong></div> <?= $order->user->user_adress_1 ?></td>
                            </tr>
                            <tr>
                                <td><div style="width:220px; float:left;"><strong>ЕДРПОУ / ИНН:</strong></div> <?= $order->user->user_pasport ?></td>
                            </tr>

                        </table>
       
                    </div>
                    <!-- /.box-body -->

                </div>                

            </div>
             <?php } ?> 
            <div class="col-lg-12 table-responsive">

                <div class="box box-widget">
                    <div class="box-header with-border">
                        <div class="user-block1">
                            <span class="username"><h4>Информация о доставке:</h4></span>
                        </div>
                        <!-- /.user-block -->
                        <div class="box-tools">
                            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                        </div>
                        <!-- /.box-tools -->
                    </div>
                    <!-- /.box-header -->
                    <div class="box-body"  id="aj_adr">
                        <?= Yii::$app->controller->renderPartial('ajax_adr', ['order' => $order, 'c_id' => $order->u_contact, 'a_id' => $order->u_adr]) ?>       
                    </div>
                    <!-- /.box-body -->

                </div> 
            </div>
            
           <?php
           if ($order->order_drop==1) { ?>
             <div class="col-lg-12 table-responsive">

                <div class="box box-widget">
                    <div class="box-header with-border">
                        <div class="user-block1">
                            <span class="username"><h4>Дропшипинг:</h4></span>
                        </div>
                        <!-- /.user-block -->
                        <div class="box-tools">
                            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                        </div>
                        <!-- /.box-tools -->
                    </div>
                    <!-- /.box-header -->
                    <div class="box-body" >
                        <?= Yii::$app->controller->renderPartial('ajax_docs', ['order' => $order, 'c_id' => $order->doc_cont, 'a_id' => $order->doc_adr]) ?>       
                    </div>
                    <!-- /.box-body -->

                </div> 
            </div>
           <?php } ?>  
            
        </div>    

    </div>


    <!-- /.row -->

</section>


<div class="box box-primary">
    <div class="box-body box-profile">

        <!-- Table row -->
        <div class="row">
            <div class="col-xs-12 ">
                <h4>Заказ:</h4>   


<?php
$tmModel = "common\models\MOrderItems";
$tmModelSearch = "common\models\search\MOrderItemsSearch";



$tm_id = '-items_' . $order_id;
$model = new $tmModel();
$msearch = new $tmModelSearch();

$dataPro = $msearch->search(Yii::$app->request->queryParams);
$dataPro->query->andFilterWhere(['order_id' => $order_id]);
$dataPro->query->andFilterWhere(['<', 'item_status', 3]);

$columns = [
    [
        'class' => 'kartik\grid\SerialColumn',
        'contentOptions' => ['class' => 'kartik-sheet-style'],
        'width' => '36px',
        'header' => '',
        'headerOptions' => ['class' => 'kartik-sheet-style']
    ],
    [
        'format' => 'html',
        'label' => 'Фото',
        'vAlign' => 'middle',
        'value' => function ($model, $key, $index, $widget) {

            $foto = \common\models\ValTree5::find()->joinWith('tree')->where('tree.tree_id = ' . $model->tree->tree_pid . ' ')->orderBy('tree.lft')->one();

            if ($foto == '')
                return 'NaN';
            else
                return '<img src="/userfiles/foto/goods/0/' . $foto->ffoto . '" />';
        },
        'width' => '100px',
        'hAlign' => 'center', 'vAlign' => 'middle'],
    [
        'attribute' => 'treename',
        'filter' => false,
        'format' => 'html',
        'label' => 'Товар',
        'vAlign' => 'middle',
    ],
    [
        'attribute' => 'item_count',
        'width' => '130px',
        'filter' => false,
        'vAlign' => 'middle',
        'label' => 'Кол-во',
    ],
    [
        'attribute' => 'item_price',
        'width' => '140px',
        'label' => 'Цена',
        'format' => ['decimal', 2],
        'hAlign' => 'right',
        'filter' => false,
        'vAlign' => 'middle',
    ],
    [
        'attribute' => 'sumitems',
        'width' => '140px',
        'label' => 'Итого',
        'format' => ['decimal', 2],
        'hAlign' => 'right',
        'filter' => false,
        'vAlign' => 'middle',
        'pageSummary' => true,
    ],
];


echo DynaGrid::widget([
    'enableMultiSort' => false,
    'gridOptions' => [
        'panel' => null,
        'showPageSummary' => true,
        'hover' => true,
        'condensed' => true,
                        'responsive'=>true,
                'responsiveWrap'=>false,
        'toolbar' => [],
        'pjax' => true,
        'dataProvider' => $dataPro,
        'rowOptions' => function ($model, $key, $index, $column) {
            $row_color = '';

            return ['class' => $row_color . " items[]_" . $model->item_id,];
        },
        'options' => ['id' => 'grid' . $tm_id, 'class' => '']
    ],
    'columns' => $columns,
    'options' => ['id' => 'project-grid' . $tm_id, 'class' => '']
]);
?>	 


            </div>
            <!-- /.col -->
        </div>   
    </div>
</div>
                <?php
                if (Yii::$app->authManager->getAssignment('admin', Yii::$app->user->getId()) || Yii::$app->authManager->getAssignment('manager', Yii::$app->user->getId())) {
                    ?>
    <div class="box box-primary">
        <div class="box-body box-profile">
            <h4>Форма редактирования параметров заказа:</h4> 
            <div class="clients-form">




    <?php
    $form = ActiveForm::begin(['type' => ActiveForm::TYPE_VERTICAL]);

    if ($order->u_contact != '') {
        $srt_data = ArrayHelper::map(UserAdress::find()->joinWith('aCity')->select(['a_id', 'CONCAT(g_c_name,", № Отделения: ",a_adr," (",a_note,")") as a_city'])->where('c_id=' . $order->u_contact)->asArray()->all(), 'a_id', 'a_city');
    }

    echo Form::widget([
        'model' => $order,
        'form' => $form,
        'columnSize' => 'md',
        'columns' => 6,
        'attributes' => [
            'order_status' => [
                'type' => Form::INPUT_WIDGET,
                'widgetClass' => '\kartik\widgets\Select2',
                'columnOptions' => ['colspan' => 2],
                'options' => [
                    'options' => [
                        'autocomplete' => 'off',
                    ],
                    'data' => $o_statuses,
                    'pluginOptions' => [
                        'allowClear' => false,
                    ],
                ],
            ],
            'u_contact' => [
                'type' => Form::INPUT_WIDGET,
                'widgetClass' => '\kartik\widgets\Select2',
                'columnOptions' => ['colspan' => 2],
                'options' => [
                    'options' => [
                        'placeholder' => 'Выберите контакт ...',
                        'autocomplete' => 'off',
                    ],
                    'data' => ArrayHelper::map(UserContacts::find()->where('u_id=' . $order->user_id)->asArray()->all(), 'c_id', 'c_name'),
                    /* 'size' => Select2::SMALL, */
                    'pluginOptions' => [
                        'allowClear' => true, 'size' => Select2::SMALL,
                    ],
                    'pluginEvents' => [
                        "change" => 'function(e) { 
                                  $.ajax({
                                   url: "render-adr",
                                   data: {c_id: $("#morders-u_contact").val()},
                                   success: function(data) {
                                       $("#aj_adr").html(data);
                                   }                                   
                              });
                             
                            }',
                    ],
                ],
            ],
            'u_adr' => [
                'type' => Form::INPUT_WIDGET,
                'widgetClass' => '\kartik\widgets\DepDrop',
                'columnOptions' => ['colspan' => 2],
                'options' => [
                    'data' => $srt_data,
                    'options' => ['placeholder' => 'Выберите адрес ...'],
                    'type' => DepDrop::TYPE_SELECT2,
                    'select2Options' => ['pluginOptions' => ['allowClear' => true]/* ,'size' => Select2::SMALL */,],
                    'pluginOptions' => [
                        'depends' => ['morders-u_contact'],
                        'url' => Url::to(['adrlist']),
                        'loadingText' => 'Загрузка адресов ...',
                    ],
                    'pluginEvents' => [
                        "change" => 'function(e) { 
                                  $.ajax({
                                   url: "render-adr",
                                   data: {c_id: $("#morders-u_contact").val(),a_id: $("#morders-u_adr").val()},
                                   success: function(data) {
                                          $("#aj_adr").html(data);
                                   } });
                            }',
                    ],
                ],
            ]
        ]
    ]);


    echo Form::widget([
        'model' => $order,
        'form' => $form,
        'columnSize' => 'md',
        'columns' => 6,
        'attributes' => [
            'order_decnum' => [
                'type' => Form::INPUT_TEXT,
                'columnOptions' => ['colspan' => 3],
                'options' => ['placeholder' => '№ декларации'],
            ],
            'order_descr' => [
                'type' => Form::INPUT_TEXTAREA,
                'columnOptions' => ['colspan' => 3],
                'options' => ['placeholder' => 'Примечания...'],
            ],
        ],
    ]);
    ?>
                <div class="form-group">
                <?= Html::submitButton('Обновить', ['class' => 'btn btn-primary']) ?>

                </div>

                <?php ActiveForm::end(); ?>

            </div>
        </div>
    </div>
            <?php } ?> 


<!-- /.content -->














