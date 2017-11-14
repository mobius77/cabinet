<?php

use kartik\form\ActiveForm;
use kartik\builder\Form;
use yii\helpers\Html;
use common\models\User;
use frontend\modules\cabinet\models\ConfMessages;
use frontend\modules\cabinet\models\ConfList;
use frontend\modules\cabinet\models\ConfUsers;
use kartik\dialog\Dialog;
use yii\bootstrap\Modal;
use yii\widgets\Pjax;

$curr_conf = ConfList::find()->where(['cf_id' => $cf_id])->one();

/* ИНИЦИАЛИЗАЦИЯ ПЛАГИНА ПОЛОСЫ ПРОКРУТКИ ДЛЯ ВСЕХ ВКЛАДОК */

$scroll = <<< JS
    if ( $(window).width() > 768 ) {
        
    var box_hgt = $(window).height() -215  + 'px';  
        
    $('.chat').each( function() {
        
         $(this).slimScroll({
            height: box_hgt, 
            start: 'bottom',
            alwaysVisible: true
        });        
         
    }); 
        
    }
       
JS;
$this->registerJs($scroll);

/* ПОКАЗ ЗНАЧКОВ РЕДАКТИРОВАНИЯ / УДАЛЕНИЯ */

/* $ed_btns = <<< JS

  $('.my_c_msg').each( function() {
  $(this).hover(
  function () { $(this).find('.edit_btn').show(); },
  function () { $(this).find('.edit_btn').hide(); }
  );
  });

  JS;
  $this->registerJs($ed_btns); */

/* ОТКРЫВАЕМ БОКОВУЮ ПАНЕЛЬ НА БОЛЬШИХ И СРЕДНИХ ЭКРАНАХ И ПОДГОНЯЕМ ОКНО ЧАТА ПО РАЗМЕРУ */

$s_bar = <<< JS
    if ( $(window).width() > 1200 ) {
        
        $('aside.control-sidebar').addClass('control-sidebar-open');
        
        var sb_wid = $('.control-sidebar-open').width();
        var cont_wid = $('#window_section').width();
        
        $('#window_section').width(cont_wid - sb_wid );
        $('#chat_input_line').width(cont_wid - sb_wid );
        
        
        $( window ).resize(function() {
            if ( $(window).width() <= 1200 ) {
                $('aside.control-sidebar').removeClass('control-sidebar-open');
                $('#window_section').width( $('#window_section').parent().width() - 15 );
                $('#chat_input_line').width( $('#window_section').parent().width() - 15 );
                }
            if ( $(window).width() > 1200 ) {
                $('aside.control-sidebar').addClass('control-sidebar-open');
                sb_wid = $('.control-sidebar-open').width();
                cont_wid = $('#window_section').parent().width() - 30;                
                $('#window_section').width(cont_wid - sb_wid );
                $('#chat_input_line').width(cont_wid - sb_wid );
                }
        });
        
    }
       
JS;
$this->registerJs($s_bar);

/* ПРОВЕРКА ТИПА КОНФЕРЕНЦИИ (ПРИВАТНАЯ / МНОГОПОЛЬЗОВАТЕЛЬСКАЯ) */

if ($curr_conf->cf_flag > 0) {

    /* ФОРМИРУЕМ ВИДЖЕТ ВЫПАДАЮЩЕГО СПИСКА С УЧАСТНИКАМИ МНОГОПОЛЬЗОВАТЕЛТЬСКОЙ КОНФЕРЕНЦИИ */

    $conf_icon = Yii::$app->controller->renderPartial('usr_dropdown_widget', ['cf_id' => $curr_conf->cf_id]);
} else {
    /* ПОЛУЧАЕМ ID СОБЕСЕДНИКА ДЛЯ ПРИВАТНОЙ КОНФЕРЕНЦИИ */
    $companion = ConfUsers::find()->where(['c_id' => $curr_conf->cf_id])->
                    andWhere('u_id != ' . Yii::$app->user->id . ' ')->one();
    $companion_u = User::find()->where(['id' => $companion->u_id])->one();
    $companion_name = $companion_u->user_firstname;

    /* ФОРМИРУЕМ ИКОНКУ КОНФЫ И ССЫЛКУ ПРИ КЛИКЕ НА НЕЕ */

    $conf_icon = Html::a('<i class="fa fa-user" style="margin-right: 5px;"></i>' .
                    '<h3 class="box-title">' . $companion_name . '</h3>', ['/cabinet/usr/update-user', 'id' => $companion_u->id], ['class' => 'chat_usr_icon']);
}

$formm->cm_text = '';

/* ПОЛУЧАЕМ СООБЩЕНИЯ */
$mes_all_count = ConfMessages::find()->where('cf_id = ' . $cf_id)->count();
$mes_all = ConfMessages::find()->where('cf_id = ' . $cf_id . ' AND cm_date >= "' . date('Y-m-d') . '"')->orderBy('cm_date ASC')->all();
if ($mes_all == null) {
    $mes_all = ConfMessages::find()->where('cf_id = ' . $cf_id)->orderBy('cm_date ASC')->offset($mes_all_count - 5)->limit(5)->all();
}

/* РЕНДЕРИМ МОДАЛЬНОЕ ОКНО ДЛЯ УДАЛЕНИЯ И РЕДАКТИРОВАНИЯ СООБЩЕНИЙ */

echo Yii::$app->controller->renderPartial('chat_edit_modal');

/* РЕНДЕРИМ МОДАЛЬНОЕ ОКНО ДЛЯ ДОБАВЛЕНИЯ ПОЛЬЗОВАТЕЛЕЙ */

echo Yii::$app->controller->renderPartial('more_users_modal', ['conf_id' => $curr_conf->cf_id]);
?>

<!-- Main row -->
<div class="row main-chat-window">
    <!-- Left col -->
    <section class="col-lg-12 connectedSortable" id="window_section">

        <!-- Chat box -->
        <div class="box box-success box-chat" style="">

            <div class="box-header">
                <div id="c_lft_wrap" style="display: inline-block;">
                    <?php
                    /*   Pjax::begin(['id'=>'pjax-container', 'enablePushState'=>false]);  */
                    echo $conf_icon;
                    /*   Pjax::end();    */
                    ?>               
                </div>
                <?php if ($curr_conf->cf_flag > 0) { ?>
                    <!--
                    <button type="button" id="btn-c-del" class="btn btn-default pull-right">
                        <i class="fa fa-trash" style="margin-right: 5px;"></i> Удалить конференцию
                    </button>                    
                    -->
                    <button type="button" id="" class="btn btn-default pull-right"
                            data-toggle="modal" data-target="#more_users_modal">
                        <i class="fa fa-user-plus"></i>
                    </button>

                <?php } else { ?>
                    <a href="/cabinet/chat/new-conf?u_id=<?= $companion->u_id ?>" class="btn btn-default pull-right">
                        <i class="fa fa-plus" style="margin-right: 5px;"></i> Пригласить
                    </a>
                <?php } ?>
            </div>

            <div class="box-body chat " id="chat-box-<?= $cf_id ?>" style="height: 70vh; overflow: auto;">                

                <?php
                $date_record = ConfUsers::find()->where(['c_id' => $cf_id])->andWhere(['u_id' => Yii::$app->user->id])->one();
                $date_record->cu_seen = date("Y-m-d H:i:s");
                $date_record->save();

                if ($mes_all != NULL) {
                    $count = count($mes_all);
                    if ($mes_all_count > $count) {
                        ?>
                        <div class="more-container"><a rel="<?= $count ?>" rel-cf="<?= $cf_id ?>" class="btn btn-success btn-md load-more"><i class="fa fa-arrow-up"></i> Загрузить еще</a></div>

                        <?php
                    }
                    ?>
                    <div id="chat-body">
                        <?= Yii::$app->controller->renderPartial('chat_body', ['mes_all' => $mes_all]); ?>
                    <?php } ?>
                    <!-- /.item -->

                </div>
            </div>

        </div>
        <!-- /.box (chat box) -->

        <div class="" id="chat_input_line" style="padding-left: 0px;">

            <?php
            /*
              echo Html::textinput($msg_input_name, NULL, [
              'class' => '',
              'placeholder' => 'Ваше сообщение...',
              'style' => 'width:100%',

              ]);

             */
            ?>

            <form id = 'form_chat_msg' style="bottom:0px;" >
                <div class="form-row align-items-center">                    
                    <div class="col-auto">
                        <!--    <label class="sr-only" for="inlineFormInputGroup">Username</label> -->
                        <div class="input-group mb-2 mb-sm-0">                            
                            <input type="text" name="msg_msg" AUTOCOMPLETE="off"
                                   class="form-control" id="inlineFormInputGroup" placeholder="Ваше сообщение">
                            <!--
                            <textarea class="form-control" rows="3" name="msg_msg" placeholder="Ваше сообщение"></textarea>
                            -->
                            <div class="input-group-addon rec_msg_btn">
                                <a class=""><i class="fa fa-level-up"></i></a>
                            </div>
                            <input name="cf_id" value="<?= $curr_conf->cf_id ?>" type="hidden">
                        </div>
                    </div>
                </div>
            </form>

        </div>

    </section>
</div>

