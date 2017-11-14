
<?php      		
$param = array( "action" =>"/admin/adm/addeditnode",
                "mod" => "admin/adm/index",
                "id_tree" => "",
                "opti" =>"update_node",
                'tm_id' => $tm_id,
                'id_tree_parent'=>$id_tree,
              );
 // echo Yii::$app->controller->renderPartial("showForm", array('param'=>$param, 'tree'=>$model)); 
?>



<section class="content-header">
    <h1>
        Проект: <?= $model->nname ?>
    </h1>
    <ol class="breadcrumb">
        <li><a href="/cabinet"><i class="fa fa-dashboard"></i> Головна</a></li>
        <li class="active"><a href="/cabinet/users"><i class="fa fa-users"></i> Проекти</a></li>
    </ol>
</section>
<br> 
<div class="row">
    <div class="col-lg-12">
        <?= Yii::$app->controller->renderPartial('projects_form', ['object' => $object, 'obj_id' => $obj_id, 'model' => $model]) ?>
    </div>
</div>
<br>    
<?= Yii::$app->controller->renderPartial('chat', ['object' => $object, 'obj_id' => $obj_id, 'model' => $model, 'chat' => $chat]) ?>