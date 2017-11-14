<?php

use common\models\SysTemplateField;
use common\models\SysTemplate;
use common\models\TreeClass;
use yii\helpers\Html;
use kartik\dynagrid\DynaGrid;
use common\models\SysLang;

$tmModel = "common\models\ValTree" . $tm_id;
$tmModelSearch = "common\models\search\ValTree" . $tm_id . "Search";


$model = new $tmModel();
$msearch = new $tmModelSearch();
$dataPro = $msearch->search(Yii::$app->request->queryParams);


 (Yii::$app->session->get('lang')!==null) ? $lang_def=Yii::$app->session->get('lang') : $lang_def = SysLang::find()->where('lang_def=1')->one()->lang_kod;

 
 if (Yii::$app->user->identity->status==0) {
     echo "Ви не можете додавати нові проекти, поки адміністратор не перевірить Ваші дані.";
     return;
     
 }
 
 
if (Yii::$app->user->identity->role == 1) {
    $dataPro->query->andFilterWhere([
        'tree.tree_pid' => $node->tree_id,
        'user_id' => Yii::$app->user->id,
        'lang' => $lang_def,
    ]);
} else {
    $dataPro->query->andFilterWhere([
        'tree.tree_pid' => $node->tree_id,
        'lang' => $lang_def,
    ]);
}


//Получаем колонки

$columns = [];

$tm_fields = SysTemplateField::find()->where('is_show = 1 AND tm_id = ' . $tm_id . '')->orderBy('tf_poz')->all();
$tm_model = SysTemplate::find()->where('tm_id=' . $tm_id)->limit(1)->one();



foreach ($tm_fields as $field) {

    /*   if (substr($f->tf_dyspname, 0, 1) == "#") {
      $parname = substr($f->tf_dyspname, 1);
      isset($ar_par[$parname]) ? $ar_par[$parname]++ : $ar_par[$parname]=0;
      $arr = $treemodel->getTfRowName($node->tree_id,$parname,$f->tf_name,$prow['tm_id'],$ar_par[$parname]);
      if ($arr==null) continue;
      print_r($arr); echo "<br>";

      } */

    if (substr($field->tf_dyspname, 0, 1) == "#") {
        $parname = substr($field->tf_dyspname, 1);
        isset($ar_par[$parname]) ? $ar_par[$parname] ++ : $ar_par[$parname] = 0;
        $arr = $treemodel->getTfRow($node->tree_id, $parname, $tm_id, $ar_par[$parname]);
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
                $lab = '<a ' . $class . ' data-sort="' . $f->tf_name . '" href="/admin/site/index?id_tree=' . $node->tree_id . '&amp;sort=' . $sor . '"><img src="/admin/images/table_icons/' . substr($lab, 1) . '"></a>';
            } else {
                $lab = '<a ' . $class . ' data-sort="' . $f->tf_name . '" href="/admin/site/index?id_tree=' . $node->tree_id . '&amp;sort=' . $sor . '">' . $lab . '</a>';
            }

            array_push($columns, ['attribute' => $f->tf_name, 'vAlign' => 'middle', 'header' => $lab, 'format' => 'html', 'width' => $f->width . 'px']);
            break;
        /* case "1":
          array_push($columns,['name'=>$f->tf_name, 'format'=>'html', value=>'substr(strip_tags($data->'.$f->tf_name.'),0,600)', 'width'=>$f->width, ]);
          break; */
        case "2":
            array_push($columns, [
                'attribute' => $f->tf_name,
                'vAlign' => 'middle',
                /*   'filterType' =>GridView::FILTER_DATE, */
                /*     value=>'Yii::$app->dateFormatter->format("yyyy.MM.dd",strtotime($data->'.$f->tf_name.'))', */
                'label' => $f->tf_dyspname,
                'width' => $f->width . 'px',
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
                'width' => $fwidth . 'px', 'hAlign' => 'center', 'vAlign' => 'middle']);
            break;
        case "5":
            /* array_push($columns,array('name'=>$f->tf_name, 'type'=>'html', 
              value=>'$data->'.$f->tf_name.'=="ON"?CHtml::image("/img/check.png","On"):""',
              'header'=>$f->tf_dyspname, 'htmlOptions'=>array('align'=>'center', 'width'=>'50'), )); */

            array_push($columns, ['attribute' => $f->tf_name, 'format' => 'html', 'label' => $f->tf_dyspname,
                'value' => function ($model, $key, $index, $widget) use($f) {
                    return $model->{$f->tf_name} == 'ON' ? '<img src="/img/check.png" >' : '';
                },
                'width' => '50px', 'hAlign' => 'center', 'vAlign' => 'middle']);

            break;
        case "7":


            array_push($columns, ['attribute' => $f->tf_name, 'format' => 'html', 'label' => $f->tf_dyspname,
                'value' => function ($model, $key, $index, $widget) use($f) {
                    switch ($f->tf_pr1) {
                        case 2:
                            $tm_id = \common\models\SysTemplate::find()->where('node_id=' . $f->tf_pr3)->one()->tm_id;
                            $valmod = "\common\models\ValTree" . $tm_id;
                            $arr = unserialize($model->{$f->tf_name}); /*  explode(';', $model->{$f->tf_name}); */
                            $val = '';
                            for ($i = 0; $i < count($arr); $i++)
                                if ($arr[$i] != '') {
                                    $val .= $valmod::find()->where('tree_id=' . $arr[$i])->one()->nname . '; ';
                                }
                            break;
                        case 3:
                            $tm_id = \common\models\SysTemplate::find()->where('node_id=' . $f->tf_pr3)->one()->tm_id;
                            $valmod = "\common\models\ValTree" . $tm_id;
                            $arr = $model->{$f->tf_name}; /*  explode(';', $model->{$f->tf_name}); */
                            if ($arr != '')
                                $val = $valmod::find()->where('tree_id=' . $arr)->one()->nname . '';
                            break;
                        case 4:
                            $tm_id = \common\models\SysTemplate::find()->where('node_id=' . $f->tf_pr4)->one()->tm_id;
                            $valmod = "\common\models\ValTree" . $tm_id;
                            $arr = unserialize($model->{$f->tf_name}); /* explode(';', $model->{$f->tf_name}); */
                            $val = '';
                            if ($arr != null) {
                                foreach ($arr as $index)
                                    if ($arr[$i] != '')
                                        $val .= $valmod::find()->where('tree_id=' . $index)->one()->nname . '; ';
                            }
                            break;
                    }


                    return $val;
                },
                'label' => $f->tf_dyspname,
                'width' => $f->width . 'px', 'hAlign' => 'left', 'vAlign' => 'middle']);

            break;
        case 14:
            array_push($columns, ['filter' => '', 'label' => $f->tf_dyspname, 'format' => 'raw', 'value' => ${$f->tf_pr1}, 'width' => $fwidth . 'px', 'hAlign' => 'center', 'vAlign' => 'middle']);
            break;

        default:
            array_push($columns, ['attribute' => $f->tf_name, 'vAlign' => 'middle', 'label' => $dysp, 'format' => 'html', 'width' => $f->width . 'px']);
            break;
    }
}

if (Yii::$app->user->identity->role > 1) {
    array_push($columns, [
        'attribute' => 'user_firstname',
        'vAlign' => 'middle',
        'label' => 'Творець',
        'format' => 'html',
        'width' => '200px'
    ]);
}


array_push($columns,[
                'attribute' => 'p_date',
                'width' => '170px',
                'filterType' => 'kartik\daterange\DateRangePicker',
                'filterWidgetOptions' => [
                    'pluginOptions' => [
                        'locale' => ['format' => 'YYYY-MM-DD', 'separator' => ' - '],
                    ],
                ],
            ]);


/* array_push($columns, [
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
  ]); */

array_push($columns, [
    'attribute' => 'status',
    'width' => '70px',
    'format' => 'raw',
    'filterType' => '\kartik\widgets\Select2',
    'filterWidgetOptions' => [
        'pluginOptions' => ['allowClear' => false,
        ],
        'data' => [11 => 'Все', 0 => 'Новый', 1 => 'Есть замечания', 2 => 'Активный'],
    ],
    'filterInputOptions' => ['placeholder' => 'Все',
    ],
    'hAlign' => 'center',
    'value' => function ($model, $key, $index, $widget) use($field) {
        switch ($model->status) {
            case "0":
                return '<span id="stat' . $model->tree_id . '" class="officon glyphicon glyphicon-remove text-danger"></span>';
            case "1":
                return '<span id="stat' . $model->tree_id . '" class="onholdicon glyphicon glyphicon-refresh text-primary"></span>';
            case "2":
                return '<span id="stat' . $model->tree_id . '" class="onicon glyphicon glyphicon-ok text-success"></span>';
        }
    },
]);

array_push($columns, [
    'class' => 'kartik\grid\ActionColumn',
    'dropdown' => false,
    'header' => '',
    'vAlign' => 'middle',
    'template' => '{edit} {delete}',
    'buttons' => [
        'edit' => function($url, $model) {
            $url = \yii\helpers\Url::toRoute(['/cabinet/update-project', 'id' => $model->tree_id]); /* '/admin?id_tree='.$model->tree_id; */
            return Html::a('<span class="glyphicon treeicon-edit glyphicon-edit"></span>', $url, [
                        'title' => 'Редагувати',
                        'data-pjax' => '0', // нужно для отключения для данной ссылки стандартного обработчика pjax. Поверьте, он все портит
                        'class' => 'grid-action' // указываем ссылке класс, чтобы потом можно было на него повесить нужный JS-обработчик
            ]);
        },
        'delete' => function($url, $model) {
            $url = \yii\helpers\Url::toRoute(['/cabinet/index', 'id_tree' => $model->tree_id]);
            return Html::a('<span class="glyphicon treeicon-del glyphicon-remove"></span>', 'javascript:void(0);', [
                        'title' => 'Видалити',
                        'data-pjax' => '0', // нужно для отключения для данной ссылки стандартного обработчика pjax. Поверьте, он все портит
                        'class' => 'grid-delete' // указываем ссылке класс, чтобы потом можно было на него повесить нужный JS-обработчик
            ]);
        },
    ],
]);


echo Html::a('Додати', 'add-project', ['class' => 'btn btn-block btn-success buttadd']);



// генерируем код событий     if (href===undefined) href='';
$str_js = "";

$dclick = " jQuery('#project-grid" . $tm_id . " tbody tr').dblclick(function() {
        var cl = $(this).attr('class');
       
            location.href = '/cabinet/update-project?id='+cl.substr(cl.indexOf(']_')+2);
      
 
});";

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
        
        $.post( '/admin/adm/turnnode', { id: idd} ) .done(function() {
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
            ['content' =>
                /*  Html::a('<i class="glyphicon glyphicon-plus"></i>', ['/adm/addnodeone?tm_id='.$tm_node.'&id_tree='.$node->tree_id], ['data-pjax'=>0, 'type'=>'button', 'title'=>'Добавить', 'class'=>'btn btn-success']) . ' '. */
                Html::a('<i class="glyphicon glyphicon-repeat"></i>', ['/cabinet/projects'], ['data-pjax' => 0, 'class' => 'btn btn-default', 'title' => 'Сброс'])
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
        'filterModel' => $msearch,
        'rowOptions' => function ($model, $key, $index, $column) {
            $row_color = '';
            switch ($model->status) {
                case "0":
                    $row_color = 'greenrow';
                    break;
                case "1":
                    $row_color = 'yellowrow';
                    break;
                case "2":
                    $row_color = '';
                    break;
            }

            return [
                'class' => $row_color . " items[]_" . $model->tree_id,
            ];
        },
        'panel' => ['heading' => ''],
    ],
    'columns' => $columns,
    'options' => ['id' => 'project-grid' . $tm_id, 'class' => '']
]);

echo Html::a('Додати', 'add-project', ['class' => 'btn btn-block btn-success buttadd']);


$scriptonoff = " jQuery('#project-grid" . $tm_id . " .grid-delete').on('click', function(e) {
                            if (confirm('Точно видалити?'))  {
                                var cl = $(this).parent().parent().attr('class');
                                var poz = cl.indexOf('_');
                                poz = poz+1;
                                var idd = cl.substr(poz);
                                $.ajax({
                                   url: '/admin/adm/deleteitem?tree_id='+idd,
                                   data: {id: '<id>', 'other': '<other>'},
                                   success: function(data) {
                                       $.pjax.reload({container:'#project-grid" . $tm_id . "-pjax'}); 
                                   }
                                }); 
                            }
                        });";

$scriptonoff .= "    jQuery('#project-grid" . $tm_id . " .grid-clone').on('click', function(e) {
                            if (confirm('Хотите создать копию этого элемента?'))  {
                                var cl = $(this).parent().parent().attr('class');
                                var poz = cl.indexOf('_');
                                poz = poz+1;
                                var idd = cl.substr(poz);
                                $.ajax({
                                   url: '/admin/adm/cloneitem?tree_id='+idd,
                                   data: {id: '<id>', 'other': '<other>'},
                                   success: function(data) {
                                       $.pjax.reload({container:'#project-grid" . $tm_id . "-pjax'}); 
                                   }
                                }); 
                            }
                        });

";



/* $this->registerJs($str_js.$dclick.$scriptonoff.$onoffclick,$this::POS_READY); */


$pajaxscr = "jQuery('#project-grid" . $tm_id . "-pjax').on('pjax:complete', function() { " . $str_js . $dclick . $onoffclick . $scriptonoff . " })";


$this->registerJs($str_js . $dclick . $scriptonoff . $onoffclick . $pajaxscr, $this::POS_LOAD);



