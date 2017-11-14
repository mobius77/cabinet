<?php

use kartik\widgets\FileInput;
use yii\helpers\Url;
use common\models\SysTemplateField;
use common\models\SysTemplate;
use yii\helpers\Html;
use kartik\dynagrid\DynaGrid;
use common\models\SysLang;

switch ($obj) {
    case 0:
        $tm_id = 46;
        break;
    case 1:
        $tm_id = 47;
        break;
}

(Yii::$app->session->get('lang')!==null) ? $lang_def=Yii::$app->session->get('lang') : $lang_def = SysLang::find()->where('lang_def=1')->one()->lang_kod;


echo FileInput::widget([
    'name' => 'upload_image-' . $obj . '',
    'id' => 'up-' . $obj,
    'language' => 'uk',
    'options' => [
        'multiple' => true,
        'accept' => $obj==1 ? 'image/*': '*',
        
        ],
    'pluginOptions' => [
        'allowedFileTypes' => $obj==1 ? ['image'] : ['image', 'html', 'text', 'video', 'audio', 'flash', 'object'],
        'previewFileType' => 'any', 
        'uploadUrl' => Url::to(['/cabinet/project-file-upload?id='.$id]),
        ],
    'pluginEvents' => [
        'fileuploaded' => 'function(data) {
                                       
                                       $.pjax.reload({container:"#project-grid' . $tm_id . '-pjax"}); 
                                           $("#up-'.$obj.'").fileinput("refresh");
                                           $("#up-'.$obj.'").fileinput("enable");
                                   }',
        ],
]);

echo '<hr>';




$tmModel = "common\models\ValTree" . $tm_id;
$tmModelSearch = "common\models\search\ValTree" . $tm_id . "Search";

$model = new $tmModel();
$msearch = new $tmModelSearch();
$dataPro = $msearch->search(Yii::$app->request->queryParams);

$dataPro->query->andFilterWhere([
    'tree.tree_pid' => $id,
    'lang' => $lang_def,
]);

//Получаем колонки

$columns = [];

$tm_fields = SysTemplateField::find()->where('is_show = 1 AND tm_id = ' . $tm_id . '')->orderBy('tf_poz')->all();
$tm_model = SysTemplate::find()->where('tm_id=' . $tm_id)->limit(1)->one();


foreach ($tm_fields as $field) {

    if (substr($field->tf_dyspname, 0, 1) == "#") {
        $parname = substr($field->tf_dyspname, 1);
        isset($ar_par[$parname]) ? $ar_par[$parname] ++ : $ar_par[$parname] = 0;
        $arr = $treemodel->getTfRow($id, $parname, $tm_id, $ar_par[$parname]);
        if ($arr == null)
            continue;
        $f = $arr['row'];
        $dysp = $arr['param_name'];
    }
    else {

        $f = $field;
        $dysp = $f->tf_dyspname;
    }

    switch ($f->tf_type) {
        case "0":
            $lab = $dysp;
            $class = '';
            $sor = $f->tf_name;

            if (isset($_GET['sort'])) {
                if ($_GET['sort'] == $f->tf_name) {
                    $class = 'class="asc"';
                    $sor = '-' . $f->tf_name;
                }
                if ($_GET['sort'] == '-' . $f->tf_name) {
                    $class = 'class="desc"';
                }
            }

            if (substr($lab, 0, 1) == ':') {
                $lab = '<a ' . $class . ' data-sort="' . $f->tf_name . '" href="/cabinet/update-project?id=' . $id . '&amp;sort=' . $sor . '"><img src="/admin/images/table_icons/' . substr($lab, 1) . '"></a>';
            } else {
                $lab = '<a ' . $class . ' data-sort="' . $f->tf_name . '" href="/cabinet/update-project?id=' . $id . '&amp;sort=' . $sor . '">' . $lab . '</a>';
            }

            array_push($columns, [
                'class' => 'kartik\grid\EditableColumn',
                'attribute' => $f->tf_name, 
                'vAlign' => 'middle', 
                'header' => $lab, 
                'format' => 'html', 
                'width' => $f->width . 'px',
                'editableOptions' => [
                    'header' => $f->tf_dyspname,
                    'size' => 'md',
                    'language' => 'uk',
                  /*  'inputType' => \kartik\editable\Editable::INPUT_WIDGET,
                    'widgetClass' => 'textInput',*/
                   /* 'options' => [
                        'type' => \kartik\datecontrol\DateControl::FORMAT_DATE,
                        'displayFormat' => 'yyyy-MM-dd',
                        'saveFormat' => 'php:Y-m-d',
                        'options' => [
                            'pluginOptions' => [
                                'autoclose' => true,
                                'todayHighlight' => true,
                            ]
                        ],
                    ],*/
                    'pluginEvents' => [
                        'editableSuccess' => " function() { $.pjax.reload({container:'#project-grid" . $tm_id."-pjax'});} ",
                    ],
                ],
                ]);
            break;

        case "3":
            $fwidth = $f->width - 20;
            array_push($columns, ['attribute' => $f->tf_name, 'format' => 'html', 'label' => $f->tf_dyspname,
                'value' => function ($model, $key, $index, $widget) use($f) {
                    if ($model->{$f->tf_name} == '')
                        return 'NaN';
                    else
                        return '<img src="' . $f->tf_pr5 . '/tumb/' . $model->{$f->tf_name} . '">';
                },
                /*'width' => $fwidth . 'px',*/ 'hAlign' => 'center', 'vAlign' => 'middle']);
            break;

        default:
            array_push($columns, ['attribute' => $f->tf_name, 'vAlign' => 'middle', 'label' => $dysp, 'format' => 'html', 'width' => $f->width . 'px']);
            break;
    }
}



array_push($columns, ['attribute' => 'lft', 'filter' => '', 'label' => 'П', 'format' => 'raw', 'value' => function ($model, $key, $index, $widget) {
        return '<img class="lft_placeholder" src="/admin/images/sort-icon-small.png">';
    }, 'width' => '48px;', 'hAlign' => 'center', 'vAlign' => 'middle']);

array_push($columns, [
    'attribute' => 'isenable',
    'label' => 'Вкл.',
    'class' => '\kartik\grid\BooleanColumn',
    'trueLabel' => 'Вкл',
    'falseLabel' => 'Выкл',
    'trueIcon' => '<span class="onbtn onoffbtn glyphicon glyphicon-ok text-success"><em style="display:none;"></em></span>',
    'falseIcon' => '<span class="offbtn onoffbtn glyphicon glyphicon-remove text-danger"></span>',
    'hAlign' => 'center',
    'vAlign' => 'middle',
    'width' => '70px',
]);


array_push($columns, [
    'class' => 'kartik\grid\ActionColumn',
    'dropdown' => false,
    'header' => '',
    'vAlign' => 'middle',
    'template' => '{edit} {delete}',
    'buttons' => [
        'delete' => function($url, $model) {
            $url = \yii\helpers\Url::toRoute(['/cabinet/index', 'id_tree' => $model->tree_id]);
            return Html::a('<span class="glyphicon treeicon-del glyphicon-remove"></span>', 'javascript:void(0);', [
                        'title' => 'Удалить',
                        'data-pjax' => '0', // нужно для отключения для данной ссылки стандартного обработчика pjax. Поверьте, он все портит
                        'class' => 'grid-delete' // указываем ссылке класс, чтобы потом можно было на него повесить нужный JS-обработчик
            ]);
        },
    ],
]);


// генерируем код событий     if (href===undefined) href='';
$str_js = "
        var href = $('#project-grid" . $tm_id . " table thead a.asc').attr('data-sort');
        var dhref = $('#project-grid" . $tm_id . " table thead a.desc').attr('data-sort');
        var sort = false;

        if ((href===undefined)&&(dhref===undefined)) { sort=true; } 
        else { if (href===undefined) href=''; if (href=='-lft') {sort=true;}}
        if (sort)
        {
        jQuery('#project-grid" . $tm_id . " table tbody').sortable({
           /* forcePlaceholderSize: true,*/
            forceHelperSize: true,
            handle: '.lft_placeholder',
            cursor: 'move',
            items: 'tr',
            update : function () {
                jQuery('#project-grid" . $tm_id . " table.items tr').removeClass('selected');
                serial = $('#project-grid" . $tm_id . " table tbody').sortable('serialize', { key: 'r[]',attribute: 'class'});
                $.ajax({
                    'url': '/cabinet/reordertree?tree_id=" . $id . "',
                    'type': 'post',
                    'data': serial,
                    'success': function(data){
                    },
                    'error': function(request, status, error){
                        alert('Не удалось осуществить сортировку. Попробуйте попозже.');
                    }
                });
            },
            helper: function(e, ui) {
            ui.children().each(function() {
                $(this).width($(this).width());
            });
            return ui;
          }
        });
        };
    ";


$onoffclick = " jQuery('#project-grid" . $tm_id . " .onoffbtn').click(function() {
        var cl = $(this).parent().parent().attr('class');
        var poz = cl.indexOf('_');
        poz = poz+1;
        var idd = cl.substr(poz);
        $(this).toggleClass('onbtn');
        $(this).toggleClass('offbtn'); 
        
        $(this).toggleClass('glyphicon-ok');
        $(this).toggleClass('glyphicon-remove'); 
        
        $(this).toggleClass('text-success');
        $(this).toggleClass('text-danger')
        
        $.post( '/cabinet/turnnode', { id: idd} ) .done(function() {
         /*  $.pjax.reload({container:'#project-grid" . $tm_id . "'});  */
            
        });
        
       
});";

echo DynaGrid::widget([
    'enableMultiSort' => false,
    /*    'pluginOptions' => [ 'language' => 'ru', ], */
    'gridOptions' => [
        'hover' => true,
        /*  'condensed'=>true, */
        'toolbar' => [
            ['content' =>''
                /*  Html::a('<i class="glyphicon glyphicon-plus"></i>', ['/adm/addnodeone?tm_id='.$tm_node.'&id_tree='.$id], ['data-pjax'=>0, 'type'=>'button', 'title'=>'Добавить', 'class'=>'btn btn-success']) . ' '. */
               
            ],
        /*   ['content'=>'{dynagridFilter}{dynagridSort}'],
          '{export}', */
        ],
        'pjax' => true,
        'pjaxSettings' => [
        /* 'pjax:complete'=>'function() {
          alert("ASASASASAS");
          })' */
        ],
        'dataProvider' => $dataPro,
        /*  'filterModel'=>$msearch, */
        'rowOptions' => function ($model, $key, $index, $column) {
            return [
                'class' => "items[]_" . $model->tree_id,
            ];
        },
        'panel' => ['heading' => ''],
    ],
    'columns' => $columns,
    'options' => ['id' => 'project-grid' . $tm_id, 'class' => '']
]);

$scriptonoff = " jQuery('#project-grid" . $tm_id . " .grid-delete').on('click', function(e) {
                            if (confirm('Точно удалить?'))  {
                                var cl = $(this).parent().parent().attr('class');
                                var poz = cl.indexOf('_');
                                poz = poz+1;
                                var idd = cl.substr(poz);
                                $.ajax({
                                   url: '/cabinet/deleteitem?tree_id='+idd,
                                   data: {id: '<id>', 'other': '<other>'},
                                   success: function(data) {
                                       $.pjax.reload({container:'#project-grid" . $tm_id . "-pjax'}); 
                                   }
                                }); 
                            }
                        });";


$pajaxscr = "jQuery('#project-grid" . $tm_id . "-pjax').on('pjax:complete', function() { " . $str_js . $dclick . $onoffclick . $scriptonoff . " })";

$this->registerJs($str_js . $dclick . $scriptonoff . $onoffclick . $pajaxscr, $this::POS_READY);



