<?php

use yii\helpers\Html;
use kartik\form\ActiveForm;
use kartik\builder\Form;
use yii\helpers\Url;
use kartik\widgets\Select2;
use frontend\modules\cabinet\models\ConfUsers;
use frontend\modules\cabinet\models\ConfMessages;
use common\models\User;
use frontend\modules\cabinet\models\ConfList;
use frontend\modules\cabinet\models\ConfForm;

$formm = new ConfForm;

$users = ConfUsers::find()->
        joinWith('u')->
        where('conf_users.u_id NOT IN (SELECT u_id FROM conf_users WHERE c_id = ' . $conf_id . ')')->
        groupBy(['conf_users.u_id'])->
        all();

$usr_data = [];

if ($users != NULL) {
    foreach ($users as $user) {
        $usr_data[$user->u_id] = $user->u->user_firstname;
    }
}

?>
<div class="modal fade" id="more_users_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="">Добавить пользователей</h4>
            </div> 
            <?php
                $form = ActiveForm::begin(['type' => ActiveForm::TYPE_HORIZONTAL, /* 'enableAjaxValidation'=>true, */
                            /*'enableClientValidation' => true,*/
                            'id' => 'cf_usr_add_form',
                            'options' => [
                                'enctype' => 'multipart/form-data',
                            ],
                ]);
                ?>
                <div class="modal-body" >

                    <?php
                    echo Select2::widget([
                        'name' => 'usr_add_list',
                        'data' => $usr_data,
                        'showToggleAll' => false,
                        'options' => [
                            'placeholder' => 'Выбрать...',
                            'multiple' => true
                        ],
                    ]);
                    
                    echo Html::hiddenInput('cf_id', $conf_id);
                    ?>

                </div>      
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Отмена</button>
                    <button type="submit" class="btn btn-primary usr_add_confirm" >Сохранить</button>
                </div>
             <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>