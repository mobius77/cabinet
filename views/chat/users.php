<?php

use frontend\modules\cabinet\models\ConfUsers;
use frontend\modules\cabinet\models\ConfMessages;
use common\models\User;
use frontend\modules\cabinet\models\ConfList;

$curr_usr = User::find()->where(['id' => Yii::$app->user->id])->one();

/* ИЩЕМ ПОЛЬЗОВАТЕЛЕЙ ДЛЯ ЧАТА */
$all_ch_users = User::find()->
        where('role > 1')->
        andWhere('id != ' . Yii::$app->user->id . '')->
        orderBy('user_firstname ASC')->
        all();

if ( isset($cf_id) ) {    
    $chat_id = $cf_id;
} else {
    $chat_id = \Yii::$app->request->get('u_id');
}
?>


<div style="margin-left: 5px; text-align: center;">
    <h4 class="control-sidebar-heading" style="float: left;">Статус</h4>

    <div class="btn-group" data-toggle="btn-toggle" style="text-align: center; margin-top: 15px;">
        <button type="button" data-ajax="1" 
                class="btn btn-default btn-sm <?= $curr_usr->user_pasport_descr == '1' ? 'active' : '' ?> stat_btn" 
                data-toggle="tooltip" title="На связи"><i class="fa fa-check text-green"></i>
        </button>
        <button type="button" data-ajax="2" 
                class="btn btn-default btn-sm <?= $curr_usr->user_pasport_descr == '2' ? 'active' : '' ?> stat_btn" 
                data-toggle="tooltip" title="На выезде"><i class="fa fa-car text-yellow"></i>
        </button>
        <button type="button" data-ajax="3" 
                class="btn btn-default btn-sm <?= $curr_usr->user_pasport_descr == '3' ? 'active' : '' ?> stat_btn" 
                data-toggle="tooltip" title="Недоступен"><i class="fa fa-close text-red"></i>
        </button>
    </div>
</div>

<div>
    <h4 class="control-sidebar-heading" style="text-align: center; margin-bottom: 0px;">Пользователи</h4>


    <ul class="nav nav-stacked c_usr_list">
        <?php
        if ($all_ch_users != NULL) {
            foreach ($all_ch_users as $one_ch_user) {

                $conf = ConfList::find()->
                        where('cf_id in (SELECT c_id FROM conf_users WHERE (`u_id`=' . $one_ch_user->id . ' OR `u_id`=' . Yii::$app->user->id . ')  GROUP BY c_id HAVING count(u_id)=2) AND  cf_flag=0')->
                        one();

                $visit_date = ConfUsers::find()->
                        where(['u_id' => Yii::$app->user->id])->
                        andWhere(['c_id' => $conf->cf_id])->
                        one();

                $mes_unseen = ConfMessages::find()->
                        where(['cf_id' => $conf->cf_id])->
                        andWhere('cm_date > "' . $visit_date->cu_seen . '" ')->
                        andWhere('otp_id != ' . Yii::$app->user->id . ' ')->
                        count();

                $cur_chat = '';
                if ($chat_id == $one_ch_user->id)
                    $cur_chat = ' active ';

                /* switch ($one_ch_user->user_pasport_descr) {
                  case '1':
                  $curr_ico = 'fa fa-check text-green';
                  break;
                  case '2':
                  $curr_ico = 'fa fa-car text-orange';
                  break;
                  case '3':
                  $curr_ico = 'fa fa-close text-red';
                  break;
                  }
                 */
                $curr_ico = 'fa fa-circle text-green';
                ?>
                <li class="<?= $cur_chat ?>">
                    <a class="chat_usr" href="/cabinet/chat/conf-main?u_id=<?= $one_ch_user->id ?>">
                        <i class="<?= $curr_ico ?>"></i><?= $one_ch_user->user_firstname ?>
                        <span class="pull-right badge bg-blue" id="unseen_<?= $one_ch_user->id ?>"><?= $mes_unseen > 0 ? $mes_unseen : '' ?></span>
                    </a>
                </li>

                <?php
                unset($curr_ico);
                unset($visit_date);
                unset($mes_unseen);
            }
        }
        ?>

    </ul>

</div>

