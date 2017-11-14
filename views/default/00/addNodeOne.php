
<table border="0" cellspacing="0" cellpadding="0" width="100%"><tr><td>
<?php      		
$param = array( "action" =>"/admin/adm/addeditnode",
                "mod" => "admin/adm/index",
                "id_tree" => "",
                "opti" =>"update_node",
                'tm_id' => $tm_id,
                'id_tree_parent'=>$id_tree,
              );
  echo Yii::$app->controller->renderPartial("showForm", array('param'=>$param, 'tree'=>$model)); 
?>
</td></tr></table> 
