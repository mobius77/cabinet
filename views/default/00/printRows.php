<?php

use yii\helpers\Html;
use common\models\SysTemplate;
use common\models\SysTemplateField;
use common\models\Tree;
 use kartik\widgets\DatePicker; 
/*use yii\jui\DatePicker;*/
use dosamigos\multiselect\MultiSelect;
use yii\helpers\ArrayHelper;
use yii\db\Query;
use kartik\widgets\ColorInput;
use kartik\widgets\TouchSpin;
use kartik\widgets\Select2; 
use yii\web\JsExpression;
use kartik\icons\Icon;
use common\models\SysLang;

Icon::map($this);

/* use dosamigos\tinymce\TinyMce; */


/*
 * Выводит форму согласно параметрам заданным в $prow используя соединение $db
 * $param список внешних дополнительных параметров формы
 * 
 * Если $param['id_tree']==0, значит это добавление нового элемента, и $id_node_tree храниься в $param['id_tree_parent']
 */


($param['id_tree'] == 0) ? $id_node_tree = $param['id_tree_parent'] : $id_node_tree = $param['id_tree'];


/* $access = new AdmModule; */
//  $item = $tree->Model->findByPk($id_node_tree);
$acc = 3;
/* if (($param['id_leaf']!='')||($_GET['opti']=='add_leaf'))
  {
  $acc=$access->checkAccessTm(Yii::app()->user->id, $id_node_tree, $prow['tm_id']);
  if ($acc<1) return false;
  }
  else
  {
  $acc=$access->checkAccess(Yii::app()->user->id, $id_node_tree);
  if ($acc<1) return false;
  }
 */

$html = '';

$fields = SysTemplateField::find()->where("tm_id = " . $prow['tm_id'])->orderBy('tf_poz')->all();

/* $result = $tree->getFields($param['id_tree_parent'],$prow['tm_id']); */


if (count($fields) > 0) {
    $elem = -1;
    $tmModel = "common\models\ValTree" . $prow['tm_id'];

    /* Если это новый элемент то пишем, что создается новый элемент  */
    if ($param['id_tree'] == 0) {
        $ttt = SysTemplate::findOne($prow['tm_id']);
        $valrow = new $tmModel();
        $html.= '<div class="add_new_element">Новый элемент: "' . $ttt->tm_name . '"</div>';
    }
    if ($param['id_tree'] > 0) {
        $valrow = $tmModel::find()->where('tree_id=' . $param['id_tree'].' AND lang="'.Yii::$app->session->get('lang').'"')->one();
    }

    foreach ($fields as $field) {
        $elem++;
/*
 * Механизм подстановки полей.
 * Если есть необходимость создать разнотипные виды одной сущности (товары с различными параметрами - одн пааметры могут быть общими, другие разными), то 
 * создаем наборы параметров:
 *  - сущность "Параметр": nname, param, foto
 *      * nname - Внутреннее название параметра
 *      * param - название параметра у сущности (то к которому будет лепиться, пример: p1, p2, p3 ...)
 *      * foto - иконка параметра (будет отображаться и в бэкэнде и во фронте)
 *  - сущность "Набор параметров": в которую входят параметры (через поле "категория - еденичный выбор")
 *      * nname - название для отображения (таким образом один и тот же параметр в разных наборах может называться по-разному)
 *      * param_id - id параметра из сущности "Параметр"
 * 
 * если название для отображения первым символом содержит "#", то это подставное поле.
 * Так как порядок полей в наборе не обязательно совпадает с порядком полей в текущей сущности, то нужно найти поле, которое соответствует текущему положения и показать его
 * Необходимо запоминать сколько подставных полей данного набора мы уже показали чтобы каждый раз показывать следующее. Таком образом все поля из набора будут 
 * показаны на своих местах. Кроме того, может быть использовано несколько наборов (хотя не совсем понятно зачем это может понадобиться).
 * 
 * Для этого создаем метод getTfRow в классе TreeClass, которая по заданным параметрам будет определять какое поле нужно подставить в данный момент.
 * 
 */
        
        
        if (substr($field->tf_dyspname, 0, 1) == "#") {
            $parname = substr($field->tf_dyspname, 1);
            isset($ar_par[$parname]) ? $ar_par[$parname]++ : $ar_par[$parname]=0;
            $arr = $tree->getTfRow($param['id_tree_parent'],$parname,$prow['tm_id'],$ar_par[$parname]);
            if ($arr==null) continue;
            $f=$arr['row'];
            $dysp = /*'<strong>'.$arr['param_name'].'</strong> */ '<img height="34" src="'.$arr['param_foto'].'"/>';
        }
        else
        {
            $dysp='';
            $f = $field;
        }
        

        /*   if ($valrow->{$f->tf_name} == "&nbsp;")
          $valrow->{$f->tf_name} = ""; */

        /*
          $html.= "<tr><td>\n";
          $html.= "   <div class='row-labels col-lg-2'>";
          if (substr($f->tf_dyspname, 0, 1) == ":") {
          $html.= "<img src='/admin/images/table_icons/" . substr($f->tf_dyspname, 1) . "'>";
          } else {
          $html.= "<strong>" . $f->tf_dyspname . "</strong>";
          }
          $html.= ":</div>"; */


        $html.= "<div class='row' style='margin:10px 0;' >";
        $html.= "   <div class='row-labels col-lg-2'>";
        if (substr($f->tf_dyspname, 0, 1) == ":") {
            $tmp = "";
            eval('$tmp=' . substr($f->tf_dyspname, 1));
            $html.= $tmp;
        } else {
            $dysp=='' ? $html.= "<strong>" . $f->tf_dyspname . "</strong>" : $html.=$dysp;
        }
        
        if (Yii::$app->session->get('lang')!=SysLang::find()->where('lang_def=1')->one()->lang_kod) {
            $html .= Html::a(Icon::show('language', ['style'=>'font-size:20px;']), 'javascript:void(0);', [
                                                                "live" => false,
                                                                "id" => 'lang_tr_' .$elem.'_'. $id_node_tree,
                                                                'class'=>'my-translate',
                                                                'onclick' => "
                                                                  if (confirm('Перенести текст с основоного языка?'))  {  
                                                                   $.ajax({
                                                                       type     :'POST',
                                                                       cache    : false,
                                                                       data     : {lang:'".Yii::$app->session->get('lang')."' ,tm_id:".$prow['tm_id'].",tf_name:'".$f->tf_name."' ,tree_id:" . $id_node_tree . "},
                                                                       url  : '/admin/adm/get-value-by-tf',
                                                                       success  : function(response) {
                                                                                   location.reload(); 
                                                                               }
                                                                       });
                                                                       }
                                                                   return false;",
                                                            ]);
            $html .= Html::a(Icon::show('file-text-o', ['style'=>'font-size:20px;']), 'javascript:void(0);', [
                                                                "live" => false,
                                                                "id" => 'lang_tr_' .$elem.'_'. $id_node_tree,
                                                                'class'=>'my-translate',
                                                                'onclick' => "
                                                                  if (confirm('Перенести текст с основоного языка?'))  {  
                                                                   $.ajax({
                                                                       type     :'POST',
                                                                       cache    : false,
                                                                       data     : {lang:'".Yii::$app->session->get('lang')."' ,tm_id:".$prow['tm_id'].",tf_name:'".$f->tf_name."' ,tree_id:" . $id_node_tree . "},
                                                                       url  : '/admin/adm/get-value-by-tf-translate',
                                                                       success  : function(response) {
                                                                                   location.reload(); 
                                                                               }
                                                                       });
                                                                       }
                                                                   return false;",
                                                            ]);
        }
        
        $html.= ":<br></div>";


        switch ($f->tf_type) {
            case 0:  //текстовое поле
                {
                    $html.= "<div class='col-lg-10'>";
                    //$valrow->{$f->tf_name} = str_replace('\'','\\\'',$valrow->{$f->tf_name});
                    $valrow->{$f->tf_name} = str_replace('"', '&quot;', $valrow->{$f->tf_name});

                    if ($f->tf_pr1 > 200) {
                        $html.= "<textarea rows=\"" . ceil($f->tf_pr1 / 65) . "\" cols=\"115\" id=\"elem_" . $elem . "_" . $id_node_tree . "\" name=\"" . $f->tf_name . "\">" . $valrow->{$f->tf_name} . "</textarea>\n";
                    } else {
                        if ($f->tf_name == 'nname')
                            $style = "id = 'nname_main' autofocus style='box-shadow: 0 1px 2px #ffc0c0; border: 1px solid #c99;'";
                        else
                            $style = '';
                        $html.= "<input class=\"\" " . $style . " name=\"" . $f->tf_name . "\" type=\"text\" id=\"elem_" . $elem . "_" . $id_node_tree . "\" value=\"" . $valrow->{$f->tf_name} . "\" size=\"65\">\n";
                    }
                    $html.= "</div>";
                }
                break;
            case 1:  //текст
                {
                    $html.= "<div class='col-lg-10'>";
                    $ta = $f->tf_name;

                    $html.= "<textarea  cols=\"80\" id=\"elem_" . $elem . "_" . $id_node_tree . "\" name=\"" . $f->tf_name . "\" rows=\"10\">" . $valrow->{$f->tf_name} . "</textarea>";
                    $this->registerJs("
                                        //<![CDATA[
                                        if (CKEDITOR.instances['elem_" . $elem . "_" . $id_node_tree . "']) {
                                        CKEDITOR.remove(CKEDITOR.instances['elem_" . $elem . "_" . $id_node_tree . "']);
                                }
                                var ckeditor1 = CKEDITOR.replace( 'elem_" . $elem . "_" . $id_node_tree . "' );
                              /*  AjexFileManager.init({
                                returnTo: 'ckeditor',
                                editor: ckeditor1,
                                skin: 'dark'
                                });*/
                                //]]>
                                 ", $this::POS_READY);
                    $html.= "</div>";
                }
                break;
            case 2:  //дата
                {
                    $html.= "<div class='col-sm-10 col-md-6 col-lg-4'>";
                    if (($valrow->{$f->tf_name} != "&nbsp;") && ($valrow->{$f->tf_name} != "")) {
                        $dval = strtotime($valrow->{$f->tf_name});
                    } else {
                        $dval = strtotime("now");
                    }
                    
                     $html.= DatePicker::widget([
                                'name' => $f->tf_name,
                                'value' => strftime('%Y-%m-%d', $dval),
                                'language' => 'uk',
                                
                                'pluginOptions' => [
                                    'placeholder' => 'Select issue date ...',
                                    'todayHighlight' => true,
                                    'autoclose'=>true,
                                    'format' => 'yyyy-mm-dd',
                                ],
                    ]);
                    
                    $html.= "</div>";
                }
                break;
            case 3:  //Фото
                {
                    $html.= "<div class='col-lg-10'>";
                    $html.= "<div class='picplane'>";
                    if (($valrow->{$f->tf_name} != "&nbsp;") && ($valrow->{$f->tf_name} != "")) {
                        if ($prow['leaf_id'] == "")
                            $ss = $id_node_tree;
                        else
                            $ss = $prow['leaf_id'];
                        $ss1 = "/tumb";

                        $dim = explode('-', $f->tf_pr2);
                        $html.= "Миниатюра: " . $valrow->{$f->tf_name} . ", " . $dim[0] . "x" . $dim[1] . "<br>";

                        $html.= "<img  id=\"srcpic-" . $f->tf_id . "\" width=\"" . $dim[0] . "\" src=\"" . $f->tf_pr5 . $ss1 . "/" . $valrow->{$f->tf_name} . "\" alt=\"Миниатюра\">&nbsp;&nbsp;";
                        if ($acc > 1) {
                            $html.= Html::a("<img border = \"0\" class='delpicone' align=\"absmiddle\" alt=\"Удалить\" src=\"/admin/images/delete.gif\">", null, [
                                        "live" => false,
                                        "id" => 'dddppp' . $id_node_tree
                                            ]
                            );
                            $script = "
                        $('#dddppp" . $id_node_tree . "').on('click', function(e) {
                           if (confirm('Точно удалить?'))  {
                            $.ajax({
                               url: '/admin/adm/deletepic?id_tree=" . $ss . "&tf_id=" . $f->tf_id . "',
                               data: {id: '<id>', 'other': '<other>'},
                               
                               success: function(data) {
                                    $('#srcpic-" . $f->tf_id . "').hide('slow');
                                    
                               }
                            }); }
                        });";
                            $this->registerJs($script, $this::POS_READY);
                        }  //				"<img class=\"delp\" onclick = \"Delpic(".$ss.",".$f->tf_id.")\" style=\"cursor:pointer;\" border = \"0\"  align=\"absmiddle\" alt=\"Удалить\" src=\"admin/images/delete.gif\">" 

                        $html.= "<br><br>\n";
                    } else {
                        $html.= "<img src=\"/admin/images/nopic.png\" alt=\"Место для картинки\"><br><br>\n";
                    }

                    if ($f->tf_pr6 != "")
                        $st = $f->tf_pr6;
                    else
                        $st = "Выберите изображение";

                    /*    if ((($f->tf_pr3!="")&&($f->tf_pr4!=""))&&(($f->tf_pr3>0)&&($f->tf_pr4>0)))
                      {
                      $st2 = " (размер не меннее ".$f->tf_pr3."x".$f->tf_pr4.")";
                      }
                      else {$st2="";} */
                    $arr_img = explode('/', $f->tf_pr1);
                    $dim = $dim = explode('-', array_pop($arr_img));
                    $st2 = " (размер не меннее " . $dim[0] . "x" . $dim[1] . ")";

                    $html.= "<small>" . $st . $st2 . "</small><br>\n";
                    $html.= "<input id=\"elem_" . $elem . "_" . $id_node_tree . "\"  type=\"file\" class=\"text\" name=\"" . $f->tf_name . "\" size=\"50\"/>\n";
                    $html.="</div>";

                    $html.= "</div>";
                }
                break;
            case 4:  //файл
                {
                    $html.= "<div class='col-lg-10'>";
                    if ($valrow->{$f->tf_name} != "") {
                        if ($prow['leaf_id'] == "")
                            $ss = $id_node_tree;
                        else
                            $ss = $prow['leaf_id'];
                        $html.= "Текущий файл: <strong><span id='srcfile-" . $f->tf_id . "'>" . $valrow->{$f->tf_name} . "</span></strong>";
                        if ($acc > 1) {

                            $html.= Html::a("<img border = \"0\"  align=\"absmiddle\" alt=\"Удалить\" src=\"/admin/images/delete.gif\">", null, [
                                        "live" => false,
                                        "id" => 'dddppp' . $id_node_tree
                                            ]
                            );
                            $script = "
                                    $('#dddppp" . $id_node_tree . "').on('click', function(e) {
                                       if (confirm('Точно удалить?'))  {
                                        $.ajax({
                                           url: '/admin/adm/deletefile?id_tree=" . $ss . "&tf_id=" . $f->tf_id . "',
                                           data: {id: '<id>', 'other': '<other>'},

                                           success: function(data) {
                                                $('#srcfile-" . $f->tf_id . "').hide('slow');
                                                
                                           }
                                        }); }
                                    });";
                            $this->registerJs($script, $this::POS_READY);
                        }
                    }

                    $html.= "<br><small>Выберите файл для добавлени</small><br>\n";
                    $html.= "<input id=\"elem_" . $elem . "_" . $id_node_tree . "\"  type=\"file\" class=\"text\" name=\"" . $f->tf_name . "\" size=\"50\"/>\n";
                    $html.= "</div>";
                }
                break;
            case 5:  //Чек бокс
                {
                    $html.= "<input name=\"" . $f->tf_name . "\" id=\"elem_" . $elem . "_" . $id_node_tree . "\"  type=\"checkbox\"";
                    if ($valrow->{$f->tf_name} == "ON")
                        $html.= " checked ";
                    $html.= " value=\"ON\">\n";
                    $html.= "<strong>" . $f->tf_dyspname . "</strong>";
                }
                break;

            case 6:   //Выбор файла
                $html.= "<strong>" . $f->tf_dyspname . "</strong>" . ":&nbsp;&nbsp;файлы должны лежать в каталоге: " . $f->tf_pr1 . "<br>\n";

                $html.= " <select name=\"" . $f->tf_name . "\" id=\"elem_" . $elem . "_" . $id_node_tree . "\"  class=\"input\">";
                $html.= "<option>&nbsp;</option>";
                // открываем папку
                $catalog = $_SERVER["DOCUMENT_ROOT"];
                $dh = opendir($catalog . $f->tf_pr1 . "/");
                while ($filename = readdir($dh)) {
                    if (($filename != ".") && ($filename != "..")) {
                        //$filename1=iconv("windows-1251", "utf-8", $filename);
                        $filename1 = $filename;
                        // любые операции с вашим файлом, например
                        //$filename=substr($filename,1,strlen($filename)-1);
                        $fs = filesize($catalog . $f->tf_pr1 . "/" . $filename);
                        $ft = filetype($catalog . $f->tf_pr1 . "/" . $filename);
                        if ($valrow->{$f->tf_name} == $filename1)
                            $html.= "<option selected>" . $filename1 . "</option>";
                        else
                            $html.= "<option>" . $filename1 . "</option>";
                    }
                }

                $html.= " </select> ";
                break;
            case 7:   //Категории
                $html.= "<div class='col-lg-10'>";

                switch ($f->tf_pr1) {
                    case 1: /* Обычный выпадающий список */
                        break;
                    case 2: /* множественный выбор ЧЕКБОКСЫ */
                        /*
                         * значения разделены ';'
                         * tf_pr2 - tree_id родителя
                         * tf_pr3 - tm_id єлементов
                         */

                        $arr = unserialize($valrow->{$f->tf_name}); /* explode(';', $valrow->{$f->tf_name}); */
                        $list = Tree::find()->where('tree_pid=' . $f->tf_pr2 . ' AND tm_id=' . $f->tf_pr3 . ' AND tree_istempl=0 AND tree.is_enable=1')->orderBy('lft')->all();

                        $data1 = [];
                        foreach ($list as $item) {
                            $data1[$item->tree_id] = $item->tree_name;
                        }

                        if (!empty($data1)) {

                            $html.= MultiSelect::widget([
                                        'id' => "multi" . $f->tf_name,
                                        "options" => ['multiple' => "multiple"], // for the actual multiselect
                                        'data' => $data1, // data as array
                                        'value' => $arr, // if preselected
                                        'name' => $f->tf_name, // name for the form
                                        "clientOptions" =>
                                        [
                                            "includeSelectAllOption" => false,
                                            'numberDisplayed' => 5,
                                            'allSelectedText' => 'Выбрано все',
                                            'disableIfEmpty' => true,
                                            /* 'buttonWidth' => '100px', */
                                            /*  'dropRight' => true, */
                                            'nonSelectedText' => 'Выберите - ' . $f->tf_dyspname,
                                        ],
                            ]);
                        }
                        unset($data1);

                        break;
                    case 3: /* единичный выбор */
                        /*
                         * 
                         * tf_pr2 - tree_id родителя
                         * tf_pr3 - tm_id элементов
                         */
                        $arr = /* unserialize */$valrow->{$f->tf_name}; /* explode(';', $valrow->{$f->tf_name}); */
                        
                        $tmid = SysTemplate::find()->where('node_id='.$f->tf_pr3)->one();
                        $model='valTree'.$tmid->tm_id.'s';
                        $ffield = SysTemplateField::find()->where('tm_id='.$tmid->tm_id.' AND tf_name="ffoto"')->one();
                        
                        
                        $list = Tree::find()->with($model)->where('tree_pid=' . $f->tf_pr2 . ' AND tm_id=' . $f->tf_pr3 . ' AND tree_istempl=0 AND tree.is_enable=1')->orderBy('lft')->all();
                        /*   $data1['']='Выберите - '.$f->tf_dyspname; */
                        $data1[''] = 'не задано';
                        foreach ($list as $item) {
                            if ($ffield!=null)
                                $data1[$item->tree_id] = '<img width="20" class="flag" src="'.$ffield->tf_pr5.'/tumb/'.$item->{$model}[0]->ffoto.'"/> '.$item->tree_name;
                            else
                                $data1[$item->tree_id] = $item->tree_name;
                        }

                     /*   $html.= MultiSelect::widget([
                                    'id' => "multi" . $f->tf_name,
                                    
                                   
                                    'data' => $data1, // data as array
                                    'value' => $arr, // if preselected
                                    'name' => $f->tf_name, // name for the form
                                    "clientOptions" =>
                                    [
                                        
                                        "includeSelectAllOption" => false,
                                        'numberDisplayed' => 5,
                                        'allSelectedText' => 'Выбрано все',
                                        'disableIfEmpty' => true,
                                       
                                        'nonSelectedText' => 'не задано',
                                    ],
                        ]);
                        */




$html.= Select2::widget([
    'id' => "multis" . $f->tf_name,
    'name' => $f->tf_name,
    'data' => $data1,
    'value' => $arr,
    'hideSearch' => true,
    'options' => ['placeholder' => 'не задано'],
    'pluginOptions' => [
        'minimumResultsForSearch' => 30,           
        'escapeMarkup' => new JsExpression("function(m) { return m; }"),
        'allowClear' => true
    ],
]);
                        
                        
                        unset($data1);
                        break;
                    case 4: /* сгруппированный множественный выбор */

                        $arr = unserialize($valrow->{$f->tf_name}); /* explode(';', $valrow->{$f->tf_name}); */
                        /*   $list = Tree::find()->where(' tm_id='.$f->tf_pr4.' AND tree_istempl=0 AND tree.is_enable=1')->orderBy('lft')->all(); */

                        $query = new Query;

                        /*
                         * Если третий параметр задан, то выбираем элементы только внутри него и ниже, если он отрицательный, то поднимаемся на это количество уровней вверх и 
                         * выбираем элементы внутри этого элемента.
                         *                         */
                        if ($f->tf_pr3 != '') {

                            $id = $f->tf_pr3;
                            if ($id < 0) {
                                $t = Tree::findOne($param['id_tree']);
                                $level = $t->level + $id;
                                $id = Tree::find()->where('level=' . $level . ' AND lft<' . $t->lft . ' AND rgt>' . $t->lft)->one()->tree_id;
                            }

                            $parent = Tree::findOne($id);
                            if ($parent == null)
                                $parent = Tree::findOne(Yii::$app->request->get('id_tree'));

                            $query->select(['tree.tree_id', 'tree.tree_name', 'tree2.tree_name as tree_parent'])
                                    ->from('tree')
                                    ->leftJoin('tree as tree2', 'tree.tree_pid = tree2.tree_id')
                                    ->where(' tree.tm_id=' . $f->tf_pr4 . ' AND tree.lft>' . $parent->lft . ' AND tree.lft<' . $parent->rgt . ' AND tree.tree_istempl=0 AND tree.is_enable=1')
                                    ->orderBy('tree2.lft');
                        }
                        else {
                            $query->select(['tree.tree_id', 'tree.tree_name', 'tree2.tree_name as tree_parent'])
                                    ->from('tree')
                                    ->leftJoin('tree as tree2', 'tree.tree_pid = tree2.tree_id')
                                    ->where(' tree.tm_id=' . $f->tf_pr4 . ' AND tree.tree_istempl=0 AND tree.is_enable=1')
                                    ->orderBy('tree2.lft');
                        }



                        $command = $query->createCommand();
                        $list = $command->queryAll();
                        $data1 = ArrayHelper::map($list, 'tree_id', 'tree_name', 'tree_parent');
                        if (!empty($data1)) {

                            $html.= MultiSelect::widget([
                                        'id' => "multi" . $f->tf_name,
                                        "options" => ['multiple' => "multiple"], // for the actual multiselect
                                        'data' => $data1, // data as array
                                        'value' => $arr, // if preselected
                                        'name' => $f->tf_name, // name for the form
                                        "clientOptions" =>
                                        [
                                            "includeSelectAllOption" => false,
                                            'numberDisplayed' => 5,
                                            'allSelectedText' => 'Выбрано все',
                                            'disableIfEmpty' => true,
                                            'enableFiltering' => true,
                                            'enableCaseInsensitiveFiltering' => true,
                                            /* 'buttonWidth' => '100px', */
                                            /*  'dropRight' => true, */
                                            'enableClickableOptGroups' => true,
                                            'nonSelectedText' => 'Выберите - ' . $f->tf_dyspname,
                                        ],
                            ]);
                        }
                        unset($data1);

                        break;

                    case 5: /* сгруппированный множественный выбор для НЕСКОЛЬКИХ шаблонов (группировка по шаблонам) */

                        $arr = unserialize($valrow->{$f->tf_name}); /* explode(';', $valrow->{$f->tf_name}); */
                        /*   $list = Tree::find()->where(' tm_id='.$f->tf_pr4.' AND tree_istempl=0 AND tree.is_enable=1')->orderBy('lft')->all(); */


                        /*
                         * Во втором параметре список ID шаблонов через зяпятую
                         * 
                         * 
                         */



                        $tms = explode(',', $f->tf_pr2);
                        $sql_arr = '';
                        foreach ($tms as $tm) {
                            $model = 'val_tree_' . $tm;
                            $tm_name = SysTemplate::findOne($tm)->tm_name;
                            $sql_arr[] = ' (SELECT nname, tr_' . $tm . '.tree_id tree_id, "' . $tm_name . '" tm_name FROM ' . $model . ' tm_' . $tm . ' LEFT JOIN tree tr_' . $tm . ' ON tm_' . $tm . '.tree_id = tr_' . $tm . '.tree_id WHERE is_enable=1 ORDER BY lft) ';
                        }

                        $sql = ' SELECT tree_id, nname, tm_name FROM (' . implode('UNION', $sql_arr) . ') AS tab ';

                        $list = Yii::$app->db->createCommand($sql)->queryAll();


                        $data1 = ArrayHelper::map($list, 'tree_id', 'nname', 'tm_name');


                        if (!empty($data1)) {

                            $html.= MultiSelect::widget([
                                        'id' => "multi" . $f->tf_name,
                                        "options" => ['multiple' => "multiple"], // for the actual multiselect
                                        'data' => $data1, // data as array
                                        'value' => $arr, // if preselected
                                        'name' => $f->tf_name, // name for the form
                                        "clientOptions" =>
                                        [
                                            "includeSelectAllOption" => false,
                                            'numberDisplayed' => 5,
                                            'allSelectedText' => 'Выбрано все',
                                            'disableIfEmpty' => true,
                                            'enableFiltering' => true,
                                            'enableCaseInsensitiveFiltering' => true,
                                            /* 'buttonWidth' => '100px', */
                                            /*  'dropRight' => true, */
                                            'enableClickableOptGroups' => true,
                                            'nonSelectedText' => 'Выберите - ' . $f->tf_dyspname,
                                        ],
                            ]);
                        }
                        unset($data1);
                        break;
                }
                $html.= "</div>";
                break;

            case 8:  //Рассылка новостей
                {
                    $html.= "<input name=\"" . $f->tf_name . "\" id=\"elem_" . $elem . "_" . $id_node_tree . "\"  type=\"checkbox\"";
                    if ($valrow->{$f->tf_name} == "1")
                        $html.= " checked ";
                    $html.= " value=\"1\">\n";
                    $html.= $f->tf_dyspname . " (для повторной отправки снимите отметку)\n";
                }
                break;

            case 9:  //произвольный код
                {
                    eval('$html.=' . $f->tf_pr7);
                }
                break;
            case 10:  //Категория список
                {
                    $tm = $f->tf_pr1;
                    $tmModel = "ValTree" . $tm;

                    if ($f->tf_pr2 != "") {
                        $sel_item = $tree->SelNode;
                        $sel_model = $tree->Model->find(array('condition' => 'lft<=' . $sel_item->lft . ' AND rgt>=' . $sel_item->rgt . ' AND tree_istempl=0 and tree_isleaf=0 and is_enable=1 and level=' . $f->tf_pr2, 'order' => 'lft'));
                        $blocks = $tmModel::model()->with('tree')->findAll(array('condition' => 'rgt<' . $sel_model->rgt . ' and lft>' . $sel_model->lft . ' and is_enable=1', 'order' => 'lft'));
                    } else {
                        $blocks = $tmModel::model()->with('tree')->findAll(array('condition' => 'is_enable=1', 'order' => 'lft'));
                    }
                    $values = unserialize($valrow->{$f->tf_name});
                    if ($values == "")
                        $values[0] = "";
                    $html.= "<strong>" . $f->tf_dyspname . "</strong>";
                    $html.="<table class=\"tab1\" cellspacing=\"0\" cellpadding=\"5\">";
                    $b = 0;
                    if ($blocks != null) {
                        foreach ($blocks as $bl) {
                            $html.="<tr><td class=\"td1\">";
                            $html.= "<input name=\"" . $f->tf_name . "_" . $b . "\" id=\"elem_" . $elem . "_" . $id_node_tree . "_" . $b . "\"  type=\"checkbox\"";
                            if (array_search($bl->tree_id, $values) !== false)
                                $html.= " checked ";
                            $html.= " value=\"" . $bl->tree_id . "\">\n";
                            $html.= "</td><td class=\"td1\">" . $bl->dname . "</td>";
                            $html.="</tr>";
                            $b++;
                        }
                    }
                    $html.="</table>";
                    $html.= "<input type=\"hidden\" NAME=\"num_list\" value=\"" . $b . "\"> \n";
                }
                break;

            case 11:   //Выбор каталога
                $html.= "<strong>" . $f->tf_dyspname . "</strong>" . ":&nbsp;&nbsp;Папки из каталога: " . $f->tf_pr1 . "<br>\n";

                $html.= " <select name=\"" . $f->tf_name . "\" id=\"elem_" . $elem . "_" . $id_node_tree . "\"  class=\"input\">";
                $html.= "<option value=''>&nbsp;</option>";
                // открываем папку
                $catalog = $_SERVER["DOCUMENT_ROOT"];
                $dh = opendir($catalog . $f->tf_pr1 . "/");
                while ($filename = readdir($dh)) {
                    if (($filename != ".") && ($filename != "..")) {
                        //$filename1=iconv("windows-1251", "utf-8", $filename);
                        $filename1 = $filename;
                        // любые операции с вашим файлом, например
                        //$filename=substr($filename,1,strlen($filename)-1);
                        $fs = filesize($catalog . $f->tf_pr1 . "/" . $filename);
                        $ft = filetype($catalog . $f->tf_pr1 . "/" . $filename);
                        if ($valrow->{$f->tf_name} == $filename1)
                            $html.= "<option selected>" . $filename1 . "</option>";
                        else
                            $html.= "<option>" . $filename1 . "</option>";
                    }
                }

                $html.= " </select> ";
                break;
            case 12: //Выбор цвета
                $html.= "<div class='col-sm-10 col-md-6 col-lg-4'>";
                $value = $valrow->{$f->tf_name};
                if ($value == '')
                    $value = $f->tf_pr1;

                $html.= ColorInput::widget([
                            'name' => $f->tf_name,
                            'id' => "elem_" . $elem . "_" . $id_node_tree,
                            'value' => $value,
                            'size' => 'md',
                ]);
                $html.= "</div>";
                break;
            case 13: //Float
                $html.= "<div class='col-sm-10 col-md-6 col-lg-4'>";
                $value = $valrow->{$f->tf_name};
                if ($value === '')
                    $value = $f->tf_pr1;

                $f->tf_pr1 == '' ? $min = 0 : $min = $f->tf_pr1;
                $f->tf_pr2 == '' ? $max = 1000000 : $max = $f->tf_pr2;
                $f->tf_pr3 == '' ? $step = 0.01 : $step = $f->tf_pr3;
                $f->tf_pr4 == '' ? $dec = 2 : $dec = $f->tf_pr4;
                $f->tf_pr5 == '' ? $prefix = 'грн' : $prefix = $f->tf_pr5;

                $html.= TouchSpin::widget([
                            'name' => $f->tf_name,
                            'pluginOptions' => [
                               /* 'initval' => 0.00,*/
                                'min' => $min,
                                'max' => $max,
                                'step' => $step,
                                'decimals' => $dec,
                                'boostat' => 5,
                                'maxboostedstep' => 10,
                                'prefix' => $prefix,
                                'initval' => $value,
                            ],
                ]);
                $html.= "</div>";
                break;
        }
        $html.= "</div>";
    }
    $html.= "<div class='row' style='padding-left:30px;' >";
    foreach ($param as $key => $val) {
        $html.= "<input name=\"" . $key . "\" id=\"" . $key . "\" type=\"hidden\" value=\"" . $val . "\">\n";
    }
    $html.= "<input name=\"tm_id\" type=\"hidden\" value=\"" . $prow['tm_id'] . "\">\n";

    if ($acc > 1) {
        $html.= '<input type="submit" onclick="js:$(\'#is_refresh\').val(1);"  name="Submit" class="botton" value="Сохранить" />';
    }
    $html.= "</div>";
}
echo $html;
