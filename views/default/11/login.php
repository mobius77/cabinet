<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \common\models\LoginForm */

$this->title = 'Вход';
$this->params['breadcrumbs'][] = $this->title;
?>


            <?php $form = ActiveForm::begin(['id' => 'login-form', 'options' => ['class' => 'login']]); ?>
                <?= $form->field($model, 'username') ?>
                <?= $form->field($model, 'password')->passwordInput() ?>
           
                <p class="login-submit">
                    <?= Html::submitButton('Вхід', ['class' => 'login-button', 'name' => 'login-button']) ?>
                </p>  
               <?= $form->field($model, 'rememberMe')->checkbox() ?>
            <?php ActiveForm::end(); ?>
