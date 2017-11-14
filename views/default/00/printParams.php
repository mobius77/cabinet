<?php
use yii\web\View;
use common\models\AdmModule;
use common\models\Tree;
use yii\helpers\Html;
use common\models\SysTemplate;
use common\models\t;


if (Yii::$app->session->get('lang')==Yii::$app->params['def_lang']) {
    $scr ="$('#news_name').change(function() { $('#nname_main').val($('#news_name').val()); });";
    $scr .=" $('#nname_main').change(function() { $('#news_name').val($('#nname_main').val()); });";
    $this->registerJs($scr,View::POS_READY,'nname-news' );
}

if (($param['id_leaf']!='')&&($param['id_leaf']!='-1')) $id=$param['id_leaf'];
    else
        $id = $tree->SelNode->tree_id;
    
    if (($param['id_leaf']!='')) $id_node_tree=$param['id_leaf'];
    else
        $id_node_tree = $tree->SelNode->tree_id;
    $acc=3;
     /*   
        $access = new AdmModule;
        $item = $tree->Model->findByPk($id);
        
        if (($item->tree_isleaf==1)||($id==-1))
        {
            $acc=$access->checkAccessTm(Yii::app()->user->id, $item->tree_pid, $item->tm_id);
            if ($acc<1) return false;
        }
        else
        {
            $acc=$access->checkAccess(Yii::app()->user->id, $item->tree_id);
            if ($acc<1) return false;
        }
    */
    
    ($param['id_tree']==0) ? $node=null : $node = /*t::getNode($id_node_tree);*/ $tree->Model->findOne($id_node_tree);

    $style="style='box-shadow: 0 1px 2px #ffc0c0; border: 1px solid #c99;'";
    
    if ($node!=null)
    {
        $categ = $tree->db->createCommand("SELECT tm_name, node_id FROM sys_template WHERE tm_ismain=1 AND node_id = ".$node->tm_id);
        $tm_name_row = $categ->queryOne();
        $tm_name=$tm_name_row['tm_name'];
    }
    
        $html =  '<div class="row descrtree">';
                $html .= '<div class="row-labels col-lg-2">
                <strong>Название страницы:</strong></div><div class="col-lg-10">
                <input value="'.$node->tree_name.'" name="news_name" id = "news_name" class="text" type="text" size="80"/>
                </div></div>';

        $ch_count = 156-strlen($node->tree_description);
      $html .= '<div class="row descrtree">
        <div class="row-labels col-lg-2"><strong>Заголовок:</strong><br></div><div class="col-lg-10">
        <input value="'.t::t($node,'tree_title').'" name="node_title" class="text" type="text" size="80"/>
        </div></div>
        
        <div class="row descrtree">
        <div class="row-labels col-lg-2"><strong>Keywords:</strong><br></div><div class="col-lg-10">
            <textarea name="node_keys" cols="77" rows="6" class="text">'.t::t($node,'tree_keywords').'</textarea>
        </div></div>
        
        <div class="row descrtree">
        <div class="row-labels col-lg-2"><strong>Description:</strong><br></div><div class="col-lg-10">
        <textarea name="node_descr" cols="77" rows="6" class="text descrchars">'.t::t($node,'tree_description').'</textarea><br>
            <span class="charslabel">Максимальная длинна: 156 символов.</span><span class="charsost">Осталось: <em class="charscount">'.$ch_count.'</em></span>
        </div></div>
        
        <div class="row descrtree">
        <div class="row-labels col-lg-2"><strong>Url:</strong><br></div><div class="col-lg-10">
        <input '.$style.' value="'.t::t($node,'tree_url').'" name="node_url" class="text" type="text" size="80"/>
        </div></div>
';

if ($acc>2) {
  
        $html .= '<div class="row descrtree">
        <div class="row-labels col-lg-2"><strong>Вкл/выкл:</strong><br></div><div class="col-lg-10">
        <input name="node_enable" type="checkbox"';
        if (t::t($node,'is_enable')=='1') $html.=  " checked ";
        $html.=  ' value="1">
        </div></div>'; }


    
        
   /* if ((1!=1)&&($param['id_leaf']==''))*/
    {
      $html.= '<div id="moveitem">';
       $html.=  '<div class="row descrtree">
                        <div class="row-labels col-lg-2"><strong>Перемещение узла:</strong></div><div class="col-lg-10">';
      $html.= Html::a('Перемещение', '#modal-send-to-bag', [
                        'class' => "m-btn m-btn-general",
                        'onclick' => "
                                                        
                                                                   $.ajax({
                                                                       type     :'POST',
                                                                       cache    : false,
                                                                       data: {id: '".$id."'},
                                                                       url  : '/admin/adm/move-item-view',
                                                                       success  : function(response) {
                                                                                  $('#moveitem').html(response);
                                                                                  }
                                                                       });
                                                                   return false;",
                    ]);
      
       /* $html.= Yii::$app->controller->renderPartial('/adm/ajaxMove',['node'=>$node,'id'=>$id],true);*/
      $html.= '</div></div></div>';
    }   

        

        $html.=  '<div class="row descrtree">
        <div class="row-labels col-lg-2"><strong>Шаблон:</strong><br></div><div class="col-lg-10">'.$tm_name.'
        </div></div>

<div class="row descrtree">
        <div class="row-labels col-lg-2"></div><div class="col-lg-10">';
         $html.= '<input name="is_refresh" id="is_refresh" type="hidden" value="0">';
         $html.= '<input name="id_tree" id="id_tree" type="hidden" value="'.$param['id_tree'].'">';

        if ($acc>1) { 
                     $html.= '<input type="submit" onclick="js:$(\'#is_refresh\').val(1);"  name="Submit" class="botton" value="Сохранить" />';
         
                /*    $html.= CHtml::ajaxSubmitButton('Сохранить и остаться','?r=admt/adm/addEditNode',
                                            array(
                                                'success'=>'function(data,status){ 
                                                               	alert("Успешно сохранено!");	
                                                                }',
                                                'complete'=>'function(data,status){ 
                                                               	if ($("#id_leaf").val()==-1) location.reload();
                                                                }',
                                            ),
                                            array(
                                                'onclick'=>'js:for (instance in CKEDITOR.instances) {
                                                   CKEDITOR.instances[instance].updateElement();
                                                     }', 
                                               "live"=>false,
                                               "id"=>'edit_param'.$id,
                                            )
                                        ); 
        */
         
         
        }
        
        
        if (($node->tree_readonly!="1")&&($acc>2)) {

        $html.='&nbsp;&nbsp;'.Html::a('Удалить <img  border = 0  align="absmiddle" alt="Удалить" src="/admin/images/del16.png">' ,'#',
                                    [	
                                           "live"=>false,
                                           "id"=>'del'.$id,
                                    ]
                            );
                    $script = "
                        $('#del".$id."').on('click', function(e) {
                           if (confirm('Точно удалить?'))  {
                            $.ajax({
                               url: '/admin/adm/deleteitem?tree_id=".$id."',
                               data: {id: '<id>', 'other': '<other>'},
                               
                               success: function(data) {
                                    window.location.replace('/admin?id_tree=".$node->tree_pid."');
                               }
                            }); }
                        });";
                    $this->registerJs($script, $this::POS_READY);
        }
        
        $html.= '</div></div>';
        echo $html;
