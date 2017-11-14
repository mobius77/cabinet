<?php

use yii\helpers\Html;
/*use yii\bootstrap\ActiveForm;*/

use yii\helpers\Url;

use common\models\LoginForm;
use kartik\form\ActiveForm;
use kartik\builder\Form;
use frontend\models\SignupForm;



?>
<div class="container ind_blog_row" style="margin-top: 20px;">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 breadcr">
        <a href="<?= Url::toRoute(['/']) ?>">
            <i class="fa fa-home" aria-hidden="true"></i>&nbsp;
        </a>

        <i class="fa fa-angle-right" aria-hidden="true"></i>&nbsp;

        <a href="<?= Url::toRoute(['/' . $current_pg->tree->tree_url]) ?>">
            <?= $current_pg->nname ?>
        </a>   
    </div>
    <h1 style="margin-bottom: 0px;"><?= $current_pg->nname ?></h1>

    <?php
    
    
    if (Yii::$app->session->hasFlash('signup')) {
        ?>
        
    <div class="alert alert-success alert-visible" style="margin: 20px 0;" >
                            <span class="alert-market">
						  	<i class="fa fa-thumbs-up"></i>
						  </span>
                         <?=   Yii::$app->session->getFlash('signup') ?>
                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true"><i class="fa fa-times"></i></button>
                        </div>
    
    <?php    
    }
    
    ?>
    

    
    <div class="row">
        <div class="col-sm-8 post-wide" style="max-width: 810px;">
            <article class="post post--preview" style="max-width: 695px; margin-top: 30px; margin-bottom: 10px;">

                <p class="art_p"><?= $current_pg->content1 ?></p>

            </article>
        </div>

        
        <?php
        
         $model = new LoginForm();
         
        
        
        ?>
        
        <aside class="col-sm-4">
            <div class="sidebar">
                <div class="news_tags" style="background-color: rgb(255,231,135);">
                    <p style="margin-bottom: 10px; margin-top: 10px;"><?= Yii::t('main', 'Увійти в кабінет') ?></p>
                    <div class="form-wrapper">
                        
                         <?php
        $form = ActiveForm::begin(['type' => ActiveForm::TYPE_VERTICAL, /* 'enableAjaxValidation'=>true, */
                    'enableClientValidation' => true,
                    'action' => '/site/login',
                    'options' => [
                        'class' => 'contact contact--clean contact--icon'
                    ],
            'fieldConfig' => [
                            'template' => "{input}\n{hint}\n{error}",
                            
                        ],
            ]);

        ?>
                        
        <div class="contact__field-container contact__field--name">
        <?php
        echo Form::widget([
            'model' => $model,
            'form' => $form,
            'columns' => 1,
            'columnSize' => 'md',
            'attributes' => [
                'username' => ['type' => Form::INPUT_TEXT, 'options' => ['placeholder' => Yii::t('main', 'Логін').' (e-mail)',
                    'class' => ' fld_norm']],
            ]
        ]);
        ?>
        </div>
                        
        <div class="contact__field-container contact__field--pass">
         <?php
         echo Form::widget([
            'model' => $model,
            'form' => $form,
            'columns' => 1,
            'columnSize' => 'md',
            'attributes' => [
                'password' => ['type' => Form::INPUT_PASSWORD, 'options' => ['placeholder' => Yii::t('main', 'Пароль'),
                    'class' => 'fld_norm']],
            ]
        ]);
        
        ?>
        </div>
                        
              <div class="text-right" style="width: 100%; margin-top: 10px; margin-bottom: 15px;">
            <?= Html::submitButton(Yii::t('main', 'Увійти'), ['class' =>'btn btn-general btn-md login_btn']) ?>
           
        </div>

        <?php ActiveForm::end(); ?>
                        
                        
              <!--      <form class="contact" id="" name="" method="post" action="/cabinet/site/login" novalidate="">
                            <input class="contact__field" name="username" placeholder="<?= Yii::t('main', 'Логін') ?> (e-mail)" type="email">
                            <input class="contact__field" name="password" placeholder="<?= Yii::t('main', 'Пароль') ?>" type="text">                           
                            <div class="text-right" style="width: 100%; margin-top: 10px;">
                                <button class="btn btn-general btn-md login_btn" type="submit"><?= Yii::t('main', 'Увійти') ?></button>
                            </div>
                        </form>  -->
                    </div>

                    <div class="news_tag_inner" style="padding-bottom: 20px;">
                        <div class="news_tag_inner" style="margin-bottom: 10px;">
                            <a href="<?= Url::toRoute(['/request-password-reset']) ?>"><?= Yii::t('main', 'Забули пароль') ?>?</a>
                        </div>
                        <div class="news_tag_inner" style="margin-bottom: 10px;">
                            <a id="regport" href="#regport"><?= Yii::t('main', 'Реєстрація на порталі') ?></a>
                        </div>
                    </div>          
                </div>
            </div>
        </aside>
    </div>

    <div  class="fw_divider"></div>

    <h2  style="margin-top: 30px;"><?= Yii::t('main', 'Реєстрація на порталі') ?></h2>
    <div class="row">        
        <div class="col-lg-6 col-md-8 col-sm-12 col-xs-12">

            <div class="form-wrapper">
                
             <?php
             
              $model = new SignupForm();
             
                $form = ActiveForm::begin(['type' => ActiveForm::TYPE_VERTICAL, /* 'enableAjaxValidation'=>true, */
                    'enableClientValidation' => true,
                    'action' => '/site/signin',
                    'options' => [
                        
                        'enctype' => 'multipart/form-data',
                        'class' => 'contact contact--clean contact--icon'
                    ],
                     'fieldConfig' => [
                            'template' => "{input}\n{hint}\n{error}",
                           
                        ],
            ]);
            ?> 
              <div class="contact__field-container contact__field--loc">
              <?php  
               echo Form::widget([
                    'model' => $model,
                    'form' => $form,
                    'columns' => 1,
                    'columnSize' => 'md',
                    'attributes' => [
                        'user_city' => ['type' => Form::INPUT_TEXT, 'options' => ['placeholder' => Yii::t('main', 'Населений пункт'),
                            'class' => 'fld_short']],
                    ]
                ]);   
               ?> 
               </div>
                
               <div class="contact__field-container contact__field--loc">
               <?php
               echo Form::widget([
                    'model' => $model,
                    'form' => $form,
                    'columns' => 1,
                    'columnSize' => 'md',
                    'attributes' => [
                        'user_index' => ['type' => Form::INPUT_TEXT, 'options' => ['placeholder' => Yii::t('main', 'Індекс'),
                             'class' => 'fld_short']],
                    ]
                ]);
               
               ?> 
               </div>
                
               <div class="contact__field-container contact__field--loc">
               <?php 
               echo Form::widget([
                    'model' => $model,
                    'form' => $form,
                    'columns' => 1,
                    'columnSize' => 'md',
                    'attributes' => [
                        'user_adress_1' => ['type' => Form::INPUT_TEXT, 'options' => ['placeholder' => Yii::t('main', 'Адреса'), 
                             'class' => 'fld_norm']],
                    ]
                ]);
               ?>
               </div>
                
               <div class="contact__field-container contact__field--case">
               <?php 
               echo Form::widget([
                    'model' => $model,
                    'form' => $form,
                    'columns' => 1,
                    'columnSize' => 'md',
                    'attributes' => [
                        'user_company' => ['type' => Form::INPUT_TEXT, 'options' => ['placeholder' => Yii::t('main', 'Повна назва підприємства'),
                            'class' => 'fld_norm']],
                    ]
                ]);
               ?>
               </div>
                
               <div class="contact__field-container contact__field--case">
               <?php 
               echo Form::widget([
                    'model' => $model,
                    'form' => $form,
                    'columns' => 1,
                    'columnSize' => 'md',
                    'attributes' => [
                       
                        'user_doc' => [
                            'type' => Form::INPUT_WIDGET, 
                            'widgetClass' => \kartik\widgets\FileInput::classname(),
                            'options' => [
                                
                                'pluginOptions'=>[
                                    
                                    'placeholder' => '',
                                    'pluginLoading' => false,
                                    'showPreview' => false,
                                    'showCaption' => true,
                                    'showRemove' => true,
                                    'showUpload' => false,
                                    
                                    ],
                             
                                ]],
                    ]
                ]);   
                ?>
               </div>
                   
               <div class="contact__field-container contact__field--name">
               <?php
               echo Form::widget([
                    'model' => $model,
                    'form' => $form,
                    'columns' => 1,
                    'columnSize' => 'md',
                    'attributes' => [
                        'user_firstname' => ['type' => Form::INPUT_TEXT, 'options' => ['placeholder' => Yii::t('main', 'Контактна особа (ПІБ)'),
                            'class' => 'fld_norm']],
                    ]
                ]);
               ?>
               </div>
                
               <div class="contact__field-container contact__field--mobile">
               <?php 
               echo Form::widget([
                    'model' => $model,
                    'form' => $form,
                    'columns' => 1,
                    'columnSize' => 'md',
                    'attributes' => [
                        'user_tel' => ['type' => Form::INPUT_TEXT, 'options' => ['placeholder' => Yii::t('main', 'Контактний телефон'),
                             'class' => 'fld_short']],
                    ]
                ]);
               ?>
               </div>
                
                <div class="contact__field-container contact__field--name">
               <?php 
               echo Form::widget([
                    'model' => $model,
                    'form' => $form,
                    'columns' => 1,
                    'columnSize' => 'md',
                    'attributes' => [
                        'username' => ['type' => Form::INPUT_TEXT, 'options' => ['placeholder' => Yii::t('main', 'Логін').' (e-mail)',
                             'class' => 'fld_short']],
                    ]
                ]); 
               ?>
                </div>
                
                <div class="contact__field-container contact__field--pass">
               <?php
               echo Form::widget([
                    'model' => $model,
                    'form' => $form,
                    'columns' => 1,
                    'columnSize' => 'md',
                    'attributes' => [
                        'password' => ['type' => Form::INPUT_PASSWORD, 'options' => ['placeholder' => Yii::t('main', 'Пароль'),
                             'class' => 'fld_short']],
                    ]
                ]);   
                
               ?>
                </div>
                    
              <div class="text-right" style="width: 100%; margin-top: 10px; margin-bottom: 15px;">
            <?= Html::submitButton(Yii::t('main', 'Відправити'), ['class' =>'btn btn-general btn-md login_btn']) ?>
           
        </div>

        <?php ActiveForm::end(); ?>  
         <!--       <form class="contact regist_form" id="" name="" method="post" action="#" novalidate="">

                    <input class="contact__field fld_short" name="contact-name" placeholder="<?= Yii::t('main', 'Населений пункт') ?>" type="email">
                    <input class="contact__field fld_short" name="contact-email" placeholder="<?= Yii::t('main', 'Індекс') ?>" type="text">                           
                    <input class="contact__field" name="contact-email" placeholder="<?= Yii::t('main', 'Адреса') ?>" type="text">
                    <input class="contact__field" name="contact-email" placeholder="<?= Yii::t('main', 'Повна назва підприємства') ?>" type="text">
                    <input class="contact__field" name="contact-email" placeholder="<?= Yii::t('main', 'Завантажити св-во про держреєстрацію') ?>" type="text">
                    <input class="contact__field" name="contact-email" placeholder="<?= Yii::t('main', 'Контактна особа (ПІБ)') ?>" type="text">
                    <input class="contact__field fld_short" name="contact-email" placeholder="<?= Yii::t('main', 'Контактний телефон') ?>" type="text">
                    <input class="contact__field fld_short" name="contact-email" placeholder="<?= Yii::t('main', 'Логін') ?> (e-mail)" type="text">
                    <input class="contact__field fld_short" name="contact-email" placeholder="<?= Yii::t('main', 'Пароль') ?>" type="text">                                       
                    <div class="text-left regist_warn">
                        <?= Yii::t('main', 'Усі поля форми обов`язкові до заповнення') ?>.<br/>
                        <?= Yii::t('main', 'Максимальний розмір файлу для завантаження - 10Мб') ?>.
                    </div>
                    <div class="text-right" style="width: 100%">
                        <button class="btn btn-general btn-md login_btn" type="submit"><?= Yii::t('main', 'Відправити') ?></button>
                    </div>
                </form>  -->
            </div>

        </div>
        <div class="col-lg-5 col-md-4 col-sm-12 col-xs-12">
            <p class="art_p"><?= $current_pg->content2 ?></p>
        </div>

    </div>
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-left sub_txt">
        <?= $current_pg->content3 ?>
    </div>




</div>

