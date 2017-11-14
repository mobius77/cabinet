<?php

use kartik\form\ActiveForm;
use kartik\builder\Form;
use yii\helpers\Html;
use common\models\User;
use frontend\modules\cabinet\models\ChatCommon;

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


/* ИЩЕМ, С КЕМ ГОВОРИМ (ДЛЯ ИНДИВИДУАЛЬНОГ ЧАТА) */
if ($companion > 0) {
    $comp_name = User::find()->where(['id' => $companion])->one();

    $mes_all = ChatCommon::find()->
            where(['otp_id' => Yii::$app->user->id])->
            andWhere(['pol_id' => $companion])->
            orderBy('cc_date ASC')->
            all();
} else {
    $comp_name->user_firstname = 'Все пользователи';
    $companion = 0;
    $mes_all = ChatCommon::find()->where(['pol_id' => 0])->orderBy('cc_date ASC')->all();
}
?>
<!-- Main row -->
<div class="row">
    <!-- Left col -->
    <section class="col-lg-10 connectedSortable" id="window_section">

        <!-- Chat box -->
        <div class="box box-success" style="">

            <div class="box-header">
                <i class="fa fa-comments-o"></i>
                <h3 class="box-title"><?= $comp_name->user_firstname ?></h3>                
            </div>

            <div class="box-body chat " id="chat-box" style="height: 70vh; overflow: auto;">                

                <!-- chat item -->

                <?php
                if ($mes_all != NULL) {
                    foreach ($mes_all as $mes_one) {
                        $author = User::find()->where(['id' => $mes_one->otp_id])->one();
                        ?>
                        <div class="item">
                            <img src="/img/user3-128x128.jpg" alt="user image" class="online">

                            <p class="message">
                                <a href="#" class="name">
                                    <small class="text-muted pull-right"><i class="fa fa-clock-o"></i> 
                                        <?= date("H:i", strtotime($mes_one->cc_date)) ?>
                                    </small>
                                    <?= $author->user_firstname ?>
                                </a>
                                <?= $mes_one->text ?>
                            </p>
                            <!--
                            <div class="attachment">
                                <h4>Attachments:</h4>

                                <p class="filename">
                                    Theme-thumbnail-image.jpg
                                </p>

                                <div class="pull-right">
                                    <button type="button" class="btn btn-primary btn-sm btn-flat">Open</button>
                                </div>
                            </div>
                            <!-- /.attachment -->
                        </div>

                        <?php
                    }
                }
                ?>
                <!-- /.item -->


            </div>
            <!-- /.chat -->
            <!--
            <div class="box-footer">
                
            </div>
            -->
        </div>
        <!-- /.box (chat box) -->

        <div class="col-lg-12">
            <!--
            <form action="/cabinet/chat/chat-main" method="post">
            <div class="input-group">                
                    <input class="form-control" name="ch_message" placeholder="Type message..." >
                    <input type="hidden" name="pol" value="<?= $companion ?>">
                    <div class="input-group-btn">
                        <button type="submit" class="btn btn-success"><i class="fa fa-plus"></i></button>
                    </div>                
            </div>
            </form>
            -->
            <?php
            $form = ActiveForm::begin([
                        'id' => 'rec_mes_form',
                        'type' => ActiveForm::TYPE_HORIZONTAL, /* 'enableAjaxValidation'=>true, */                        
                        'enableClientValidation' => true,
                        'options' => [
                            
                            'enctype' => 'multipart/form-data',
                        ],
            ]);

            echo $form->field($formm, 'text', [
                'addon' => [
                    'append' => [
                        'content' => Html::submitButton('<i class="fa fa-level-up"></i>', ['class' => 'btn btn-primary']),
                        'asButton' => true
                    ]
                ]
            ]);

            echo Form::widget([
                'model' => $formm,
                'form' => $form,
                'columns' => 2,
                'attributes' => [// 2 column layout
                    'pol_id' => [
                        'label' => false,
                        'type' => Form::INPUT_HIDDEN,
                        'options' => ['value' => $companion]
                    ],
                ]
            ]);
            
            ActiveForm::end();
            ?>

        </div>

    </section>
</div>

