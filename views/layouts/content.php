<?php

use yii\widgets\Breadcrumbs;
use dmstr\widgets\Alert;
use common\models\User;


$curr_usr = User::find()->where(['id' => Yii::$app->user->id])->one();

$curr_date = date("Y-m-d H:i:s");

?>
<div class="content-wrapper">
    <section class="content-header">
        <?php /* if (isset($this->blocks['content-header'])) { ?>
          <h1><?= $this->blocks['content-header'] ?></h1>
          <?php } else { ?>
          <h1>
          <?php
          if ($this->title !== null) {
          echo \yii\helpers\Html::encode($this->title);
          } else {
          echo \yii\helpers\Inflector::camel2words(
          \yii\helpers\Inflector::id2camel($this->context->module->id)
          );
          echo ($this->context->module->id !== \Yii::$app->id) ? '<small>Module</small>' : '';
          } ?>
          </h1>
          <?php } */ ?>

        <?=
        Breadcrumbs::widget(
                [
                    'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
                ]
        )
        ?>
    </section>

    <section class="content">
        <?= Alert::widget() ?>
        <?= $content ?>
    </section>
</div>

<!--<footer class="main-footer">
    <div class="pull-right hidden-xs">

    </div>

</footer> -->

<!-- Control Sidebar  control-sidebar-open -->
<?php
if (Yii::$app->authManager->getAssignment('admin', Yii::$app->user->getId())||Yii::$app->authManager->getAssignment('manager', Yii::$app->user->getId())) { ?>
<aside class="control-sidebar control-sidebar-dark ">
    
        <div class="right-sidebar" style="padding: 0px 0px;" >
           
        
         <ul class="nav nav-tabs nav-justified control-sidebar-tabs">
            <li class="active"><a href="#control-sidebar-home-tab" data-toggle="tab"><i class="fa fa-user"></i><div style="font-size: 9px; ">Чат</div></a></li>
            <li><a href="#control-sidebar-settings-tab" data-toggle="tab"><i class="fa fa-users"></i><div style="font-size: 9px; ">Конференции</div></a></li>
         </ul>
         <div class="tab-content">
        
        <div class="tab-pane active" id="control-sidebar-home-tab">
            <div style="margin-bottom: 30px;" id="registr"><?= Yii::$app->controller->renderPartial('//../modules/cabinet/views/chat/users') ?></div>
        </div>
        <div class="tab-pane" id="control-sidebar-settings-tab">
            <div id="my-troopers"><?= Yii::$app->controller->renderPartial('//../modules/cabinet/views/chat/confs') ?></div>
        </div>
        </div>
             
    </div>
</aside><!-- /.control-sidebar -->
<?php } ?>
<!-- Add the sidebar's background. This div must be placed
     immediately after the control sidebar -->
<div class='control-sidebar-bg'></div>