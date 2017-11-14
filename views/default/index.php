<?php

use yii\helpers\Html;
use kartik\form\ActiveForm;
use kartik\builder\Form;
use kartik\widgets\Select2;
use common\models\User;
use yiicod\socketio\Broadcast;

$user_all = User::find()->
        where('role > 1')->
        /*
          andWhere('id != ' . Yii::$app->user->id . '')->
         */
        orderBy('id')->
        all();

$user_list = [];

foreach ($user_all as $user_one) {
    $user_list[$user_one->id] = $user_one->user_firstname;
    echo $user_one->user_type;
}



/*  Broadcast::emit('update_notification_count', ['mmm' => '111']); */
?>

<div id="msgdata">
    Notimoder
</div>
<div>
    <form id="my-socket" method="post" action="\cabinet\default\sendmsg">
        <input name="msg" type="text" >
        <input type="submit">
    </form>
</div>

<a id="emit"  > Subscribe!!! </a>
<br/>

<div id="msgdatan">
    Notimodern
</div>
<div>
    <!--
   <form id="my-socketn" method="post" action="\cabinet\default\sendmsgn">
      <input name="msgn" type="text" > -->
    <?php
    $form = ActiveForm::begin(['action' => ['/cabinet/default/sendmsgn'], 'id' => 'my-socketn', 'method' => 'post',]);

    echo $form->field($formm, 'cf_users')->widget(Select2::classname(), [
        'data' => $user_list,
        'options' => ['placeholder' => 'Выберите участников', 'multiple' => true],
        'pluginOptions' => [
            'allowClear' => true,
        ],
    ]);

    echo Form::widget([
        'model' => $formm,
        'form' => $form,
        'columns' => 4,
        'attributes' => [
            'cf_name' => [
                'columnOptions' => ['colspan' => 1],
                'type' => Form::INPUT_TEXT,
                'options' => ['placeholder' => '']
            ],
        ]
    ]);

    echo Html::submitButton('Отправить', ['class' => 'btn btn-primary']);

    ActiveForm::end();

    /*  echo ( Yii::$app->redis->executeCommand('CLIENT LIST')); */
    ?>

    <div class="col-lg-12" style="margin-top: 20px;">
        <form id="rec_msg_form">
            <input name="msg_msg" type="text" >
            <input name="cf_id" value="12" type="hidden">
        </form>
        <a id="rec_msg_btn"><h4>Rec msg test</h4></a>
    </div>
    
    <button id="stor_btn">STORAGE</button>

</div>