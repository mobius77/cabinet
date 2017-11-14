<?php
use frontend\modules\cabinet\models\ConfUsers;
use frontend\modules\cabinet\models\ConfMessages;
use common\models\User;
use frontend\modules\cabinet\models\ConfList;


$scroll_confs = <<< JS
    if ( $(window).width() > 768 ) {
        
    var arc_hgt = 250;  
        
    $('.archive').slimScroll({
            height: arc_hgt, 
            start: 'bottom',
            alwaysVisible: true
        });  
        
    }
       
JS;
$this->registerJs($scroll_confs);

/* ИЩЕМ КОНФЕРЕНЦИИ С ТЕКУЩИМ ПОЛЬЗОВАТЕЛЕМ */

$confs_old = ConfList::find()->           
        /*where('cf_id in (SELECT c_id FROM conf_users WHERE (`u_id`=' . Yii::$app->user->id . ')) AND  cf_flag>0')->*/
        joinWith('confUsers')->
        where(['conf_users.u_id' => Yii::$app->user->id])->
        andWhere('conf_list.cf_flag > 0')->
        orderBy('conf_list.cf_last_msg DESC')->
        offset(4)->
        all();

$confs_new = ConfList::find()->
        joinWith('confUsers')->
        where(['conf_users.u_id' => Yii::$app->user->id])->
        andWhere('conf_list.cf_flag > 0')->
        orderBy('conf_list.cf_last_msg DESC')->
        limit(4)->
        all();

?>

    <div style="">
        <h4 class="control-sidebar-heading" style="text-align: center;">Конференции</h4>

        <ul class="nav nav-stacked c_usr_list">
            <li><a href="/cabinet/chat/new-conf"><i class="fa fa-plus text-primary"></i>Добавить</a></li>            
            <?php
            if ($confs_new != NULL) {
                foreach ($confs_new as $conf_new) { 
                    $visit_date = ConfUsers::find()->
                                  where(['u_id' => Yii::$app->user->id])->
                                  andWhere(['c_id' => $conf_new->cf_id])->
                                  one();
                    
                    $mes_unseen = ConfMessages::find()->
                                  where(['cf_id' => $conf_new->cf_id])->
                                  andWhere('cm_date > "'.$visit_date->cu_seen.'" ')->
                                  /*andWhere('otp_id != '.Yii::$app->user->id.' ')->*/
                                  count();
                    
                    $cf_usr_count = ConfUsers::find()->where(['c_id' => $conf_new->cf_id])->count();
                    
                    ?>
                    <li><a href="/cabinet/chat/conf-main?cf_id=<?= $conf_new->cf_id ?>">
                            <i class="fa fa-users text-green"></i><?= mb_substr($conf_new->cf_name, 0 , 12 , "UTF-8") ?><?php
                            if (mb_strlen($conf_new->cf_name, "UTF-8") >= 12) { echo '...';}
                            ?>
                            <span class="usr_counter">
                                <i class="fa fa-user "></i>
                                <?= $cf_usr_count ?>
                            </span>
                            
                            <?= $mes_unseen > 0 ? '<span class="pull-right badge bg-blue">'.$mes_unseen.'</span>' : ''?>
                        </a>
                    </li>
        <?php   }
            unset ($visit_date); unset ($mes_unseen); unset ($cf_usr_count);
            }
            ?>
        </ul>        
    </div>
    
    <?php
    if ($confs_old != NULL) { ?>
    <div class="box box-default box-solid " style="border: none; font-size: 14px;">
        <div class="box-header " style="background: #222d32; color: #fff; padding-left: 12px; margin-top: 10px; padding-bottom: 0px;">           
            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-chevron-down" style="color: #fff;"></i>
            </button>            
            Архив
            <!-- /.box-tools -->
        </div>
        <!-- /.box-header -->
        <div class="box-body " style=" background: #222d32; border: none; border-radius: 0px; padding-top: 0px;">
            <ul class="nav nav-stacked c_usr_list archive">
                <?php                
                foreach ($confs_old as $conf_one) {
                    $visit_date = ConfUsers::find()->
                                  where(['u_id' => Yii::$app->user->id])->
                                  andWhere(['c_id' => $conf_one->cf_id])->
                                  one();
                    
                    $mes_unseen = ConfMessages::find()->
                                  where(['cf_id' => $conf_one->cf_id])->
                                  andWhere('cm_date > "'.$visit_date->cu_seen.'" ')->
                                  andWhere('otp_id != '.Yii::$app->user->id.' ')->
                                  count();
                    
                    $cf_usr_count = ConfUsers::find()->where(['c_id' => $conf_one->cf_id])->count();
                   
                    ?> 
                    <li><a href="/cabinet/chat/conf-main?cf_id=<?= $conf_one->cf_id ?>">
                            <i class="fa fa-users text-yellow"></i><?= mb_substr($conf_one->cf_name, 0 , 12 , "UTF-8") ?><?php
                            if (mb_strlen($conf_one->cf_name, "UTF-8") >= 12) { echo '...';}
                            ?>
                            <?= $mes_unseen > 0 ? '<span class="pull-right badge bg-blue">'.$mes_unseen.'</span>' : ''?>
                            <span class="usr_counter">
                                <i class="fa fa-user "></i>
                                <?= $cf_usr_count ?>
                            </span>
                        </a>
                    </li>
                <?php } ?>
            </ul>
        </div>
        <!-- /.box-body -->
    </div>
    <?php } ?>