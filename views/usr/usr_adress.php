<?php

use kartik\grid\GridView;
use yii\helpers\Html;
use yii\helpers\Url;
?>

<?php if ($b_crumb != 'adm') { ?>
    <section class="content-header" >
        <h4>
            Адреса     
        </h4>
        <ol class="breadcrumb" style="font-size: 14px;">
            <li><a href="/cabinet"><i class="fa fa-home"></i> Главная</a></li>
            <li class="active">Адреса</li>        
        </ol>    
    </section>
    <section class="content">
        <div class="box box-primary" style="border-top: none;">
        <?php } ?>    


        <?php
        
        $gridColumns = [
            [
                'class' => 'kartik\grid\SerialColumn',
                'contentOptions' => ['class' => 'kartik-sheet-style'],
                'width' => '36px',
                'header' => '#',
                'headerOptions' => ['class' => 'kartik-sheet-style']
            ],
            [
                'attribute' => 'aCity.g_c_name',
                'label' => 'Населенный пункт',
            ],
            [
                'attribute' => 'a_adr',
            ],
            [
                'attribute' => 'a_note',
            ],     
            [
                'class' => 'kartik\grid\ActionColumn',
                /*   'dropdown'=>$this->dropdown,
                  'dropdownOptions'=>['class'=>'pull-right'], */
                'template' => '{update} {delete}',
                'urlCreator' => function($action, $model, $key, $index) {
                    return Url::toRoute([$action . '-usr-adress', 'a_id' => $model->a_id]);
                },
                'updateOptions' => ['title' => 'Изменить', 'data-toggle' => 'tooltip'],
                'deleteOptions' => ['title' => 'Удалить', 'data-toggle' => 'tooltip'],
                'headerOptions' => ['class' => 'kartik-sheet-style'],
            ],
        ];



        echo GridView::widget([
            'id' => 'usr_adr_grid',
            'dataProvider' => $dataProvider,
            'filterModel' => null,
            'columns' => $gridColumns,
            'containerOptions' => ['style' => 'overflow: auto'], // only set when $responsive = false
            'headerRowOptions' => ['class' => 'kartik-sheet-style'],
            'filterRowOptions' => ['class' => 'kartik-sheet-style'],
            'pjax' => true, // pjax is set to always true for this demo
            // set your toolbar
            'toolbar' => [
                ['content' => Html::a('<i class="glyphicon glyphicon-plus"></i>', ['add-usr-adress', 'c_id' => $c_id, 'u_id' => $u_id], ['data-pjax' => 0, 'class' => 'btn btn-default', 'title' => Yii::t('kvgrid', 'Добавить адрес')])
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
                'heading' => 'Адреса',
            ],
            'persistResize' => false,
                /* 'exportConfig'=>$exportConfig, */
        ]);



        if ($b_crumb != 'adm') {
            ?>

        </div>

        <?php
        if (isset($c_id) && $c_id > 0 && Yii::$app->authManager->getAssignment('admin', Yii::$app->user->getId())) {
            $this_user = \frontend\modules\cabinet\models\UserContacts::find()->
                    where(['c_id' => $c_id])->
                    one();

            echo Html::a('К профилю пользователя', ['/cabinet/usr/update-user', 'id' => $this_user->u_id], ['class' => 'btn btn-success']);
        }
    }
    ?>
</section>

