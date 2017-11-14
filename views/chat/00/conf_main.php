<?php

use kartik\form\ActiveForm;
use kartik\builder\Form;
use yii\helpers\Html;
use common\models\User;
use frontend\modules\cabinet\models\ConfMessages;
use frontend\modules\cabinet\models\ConfList;
use frontend\modules\cabinet\models\ConfUsers;
use kartik\dialog\Dialog;

$curr_conf = ConfList::find()->where(['cf_id' => $cf_id])->one();

/* ИНИЦИАЛИЗАЦИЯ ПЛАГИНА ПОЛОСЫ ПРОКРУТКИ ДЛЯ ВСЕХ ВКЛАДОК */

$scroll = <<< JS
    if ( $(window).width() > 768 ) {
        
    var box_hgt = $(window).height() * 0.7 + 'px';  
        
    $('.chat').each( function() {
        
         $(this).slimScroll({
            height: box_hgt       
        });        
         
    }); 
        
    }
       
JS;
$this->registerJs($scroll);

/*ПОКАЗ ЗНАЧКОВ РЕДАКТИРОВАНИЯ / УДАЛЕНИЯ */

$ed_btns = <<< JS
        
        $('.my_c_msg').each( function() {
            $(this).hover( 
                function () { $(this).find('.edit_btn').show(); },
                function () { $(this).find('.edit_btn').hide(); }
                );
            });
        
JS;
$this->registerJs($ed_btns);        

/* ОТКРЫВАЕМ БОКОВУЮ ПАНЕЛЬ НА БОЛЬШИХ И СРЕДНИХ ЭКРАНАХ И ПОДГОНЯЕМ ОКНО ЧАТА ПО РАЗМЕРУ */

$s_bar = <<< JS
    if ( $(window).width() > 1200 ) {
        
        $('aside.control-sidebar').addClass('control-sidebar-open');
        
        var sb_wid = $('.control-sidebar-open').width();
        var cont_wid = $('#window_section').width();
        
        $('#window_section').width(cont_wid - sb_wid );
        
        
        $( window ).resize(function() {
            if ( $(window).width() <= 1200 ) {
                $('aside.control-sidebar').removeClass('control-sidebar-open');
                $('#window_section').width( $('#window_section').parent().width() - 15 );
                }
            if ( $(window).width() > 1200 ) {
                sb_wid = $('.control-sidebar-open').width();
                cont_wid = $('#window_section').parent().width() - 30;
                $('aside.control-sidebar').addClass('control-sidebar-open');
                $('#window_section').width(cont_wid - sb_wid );
                }
        });
        
    }
       
JS;
$this->registerJs($s_bar);

/* ВЫЗОВ ОКНА ПОДТВЕРЖДЕНИЯ УДАЛЕНИЯ КОНФЕРЕНЦИИ */

if ($curr_conf->cf_flag > 0) {

    echo Dialog::widget([
        'options' => [
            'title' => 'Удалить конференцию',
            'btnOKLabel' => 'Удалить',
            'btnCancelLabel' => 'Отмена'
        ]
    ]);

    $del_button = <<< JS

$("#btn-c-del").on("click", function() {
    krajeeDialog.confirm("Удалить данную конференцию?", function (result) {
        if (result) {
            return location.href = '/cabinet/chat/conf-del?cf_id=$cf_id';
        } else {
            return null;
        }
    });
});
        
JS;
    $this->registerJs($del_button);
} else {
    /* ПОЛУЧАЕМ ID СОБЕСЕДНИКА ДЛЯ ПРИВАТНОЙ КОНФЕРЕНЦИИ */
    $companion = ConfUsers::find()->where(['c_id' => $curr_conf->cf_id])->andWhere('u_id != ' . Yii::$app->user->id . ' ')->one();
}

$formm->cm_text = '';

/* ПОЛУЧАЕМ СООБЩЕНИЯ */

$mes_all = ConfMessages::find()->where(['cf_id' => $cf_id])->orderBy('cm_date ASC')->all();
?>
<!-- Main row -->
<div class="row">
    <!-- Left col -->
    <section class="col-lg-12 connectedSortable" id="window_section">

        <!-- Chat box -->
        <div class="box box-success" style="">

            <div class="box-header">
                <i class="fa fa-comments-o"></i>
                <h3 class="box-title"><?= $curr_conf->cf_name ?></h3>

                <?php if ($curr_conf->cf_flag > 0) { ?>
                    <button type="button" id="btn-c-del" class="btn btn-default pull-right">
                        <i class="fa fa-trash" style="margin-right: 5px;"></i> Удалить конференцию
                    </button>
                <?php } else { ?>
                    <a href="/cabinet/chat/new-conf?u_id=<?= $companion->u_id ?>" class="btn btn-default pull-right">
                        <i class="fa fa-plus" style="margin-right: 5px;"></i> Добавить пользователей
                    </a>

                <?php } ?>
            </div>

            <div class="box-body chat " id="chat-box" style="height: 70vh; overflow: auto;">                

                <!-- chat item -->
                <style>

                    .my_c_msg {
                        box-shadow: 2px 2px 3px 0px rgba(0,0,0,0.3);
                        background: rgba(60,141,188,0.2);
                    }
                    .oth_c_msg {
                        box-shadow: -2px 2px 3px 0px rgba(0,0,0,0.3);
                        background: rgba(0,0,0,0.06);
                    }
                    .my_c_msg, .oth_c_msg {
                        padding: 5px;
                    }

                    .edit_btn {
                        position: absolute;
                        top: 1px;
                        right: 60px;
                    }



                </style>

                <?php
                if ($mes_all != NULL) {
                    $date_record = ConfUsers::find()->where(['c_id' => $cf_id])->andWhere(['u_id' => Yii::$app->user->id])->one();
                    $date_record->cu_seen = date("Y-m-d H:i:s");
                    $date_record->save();

                    foreach ($mes_all as $mes_one) {
                        $author = User::find()->where(['id' => $mes_one->otp_id])->one();
                        if ($author->id == Yii::$app->user->id) {
                            $msg_class = 'my_c_msg';
                        } else {
                            $msg_class = 'oth_c_msg';
                        }
                        ?>
                        <div class="item <?= $msg_class ?>" style="position: relative;">
                            <img src="/img/user3-128x128.jpg" alt="user image" class="online">

                            <p class="message">
                                <a class="name">
                                    <small class="text-muted pull-right">
                                        <i class="fa fa-clock-o"></i>
                                        <?=
                                        date("Y-m-d", strtotime($mes_one->cm_date)) < date("Y-m-d") ?
                                                date("d.m.Y", strtotime($mes_one->cm_date)) : ''
                                        ?>
                                        <?= date("H:i", strtotime($mes_one->cm_date)) ?>
                                    </small>

                                    <?= $author->user_firstname ?>                                    
                                </a>
                                <?= $mes_one->cm_text ?>                            
                            </p>
                            <div class="btn-group edit_btn" style="display: none;">
                                <a href="/cabinet/chat/msg-edit?cm_id=<?= $mes_one->cm_id ?>" class="btn btn-default btn-xs edit_btn" data-toggle="tooltip" title="Редактировать"
                                   style="margin-right: 5px;"><i class="fa fa-pencil"></i></a>
                                <a href="/cabinet/chat/msg-del?cm_id=<?= $mes_one->cm_id ?>" class="btn btn-default btn-xs edit_btn" data-toggle="tooltip" title="Удалить"
                                   ><i class="fa fa-trash"></i></a>
                            </div>

                        </div>

                        <?php
                    }
                }
                ?>
                <!-- /.item -->


            </div>

        </div>
        <!-- /.box (chat box) -->

        <div class="col-lg-12">

            <?php
            $form = ActiveForm::begin(['type' => ActiveForm::TYPE_HORIZONTAL, /* 'enableAjaxValidation'=>true, */
                        'id' => 'rec_msg_form',
                        'enableClientValidation' => true,
                        'options' => [
                            'enctype' => 'multipart/form-data',
                        ],
            ]);

            echo $form->field($formm, 'cm_text', [
                'addon' => [
                    'append' => [
                        /*
                        'content' => Html::submitButton('<i class="fa fa-level-up"></i>', ['class' => 'btn btn-primary']),
                        'asButton' => true
                        */
                        'content' => Html::button('<i class="fa fa-level-up"></i>', ['class' => 'btn btn-primary rec_msg_btn']),
                        'asButton' => true
                        
                    ]
                ]
            ]);

            echo Form::widget([
                'model' => $formm,
                'form' => $form,
                'columns' => 2,
                'attributes' => [// 2 column layout
                    'cf_id' => [
                        'label' => false,
                        'type' => Form::INPUT_HIDDEN,
                        'options' => ['value' => $cf_id]
                    ],
                ]
            ]);

            ActiveForm::end();
            ?>

        </div>

    </section>
</div>

