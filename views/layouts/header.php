<?php

use yii\helpers\Html;
use common\models\Dialog;
use kartik\widgets\Select2;
use common\models\SysLang;
use yii\helpers\ArrayHelper;

/* @var $this \yii\web\View */
/* @var $content string */

if (Yii::$app->user->identity->role > 1) {
    $mess = Dialog::find()->joinWith('user')->where('object = "prj" AND user_id <> ' . Yii::$app->user->id . ' AND dialog.status = 0')->groupBy('object_id')->orderBy('object, d_date DESC')->all();
    $profs = Dialog::find()->joinWith('user')->where('object = "usr" AND user_id <> ' . Yii::$app->user->id . ' AND dialog.status = 0')->groupBy('object_id')->orderBy('object, d_date DESC')->all();
    /*  $projs = \common\models\ValTree33::find()->joinWith('user')->where('lang="uk" AND val_tree_33.status = 0')->orderBy('p_date DESC')->all(); */
} else {
    /*
      $mess = Dialog::find()->joinWith('user')->leftJoin('val_tree_33', 'object_id = tree_id')->where('object = "prj" AND val_tree_33.user_id =' . Yii::$app->user->id . '  AND dialog.user_id <> ' . Yii::$app->user->id . ' AND dialog.status = 0')->groupBy('object_id')->orderBy('d_date DESC')->all();
     */
    $profs = Dialog::find()->joinWith('user')->where('object = "usr" AND object_id =' . Yii::$app->user->id . '  AND dialog.user_id <> ' . Yii::$app->user->id . ' AND dialog.status = 0')->groupBy('object_id')->orderBy('d_date DESC')->all();
    /* $projs = \common\models\ValTree33::find()->joinWith('user')->where('lang="uk" AND val_tree_33.status = 0 AND val_tree_33.user_id = ' . Yii::$app->user->id . '')->orderBy('p_date DESC')->all(); */
}
?>

<header class="main-header">

    <?= Html::a('<span class="logo-mini">ІП</span><span style="font-size:18px;" class="logo-lg">Кабинет</span>', '/cabinet', ['class' => 'logo']) ?>

    <nav class="navbar navbar-static-top" role="navigation">

        <a href="#" class="sidebar-toggle" data-toggle="push-menu" role="button">
            <span class="sr-only">Toggle navigation</span>
        </a>

       
        <div class="navbar-custom-menu">

            <ul class="nav navbar-nav">



                <!-- Messages: style can be found in dropdown.less-->
                <li class="dropdown messages-menu">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                        <i class="fa fa-briefcase"></i>
                        <span class="label label-success"><?= count($mess) ?></span>
                    </a>
                    <ul class="dropdown-menu">
                        <li class="header">У Вас <?= count($mess) ?> повідомлень</li>
                        <li>
                            <ul class="menu">
                                <?php
                                if ($mess != null) {
                                    foreach ($mess as $mes) {

                                        $now = new DateTime(); // текущее время на сервере
                                        $date = DateTime::createFromFormat("Y-m-d H:i:s", $mes->d_date); // задаем дату в любом формате
                                        $interval = $now->diff($date); // получаем разницу в виде объекта DateInterval
                                        $i = $interval->i; // кол-во лет
                                        $h = $interval->h; // кол-во лет
                                        $d = $interval->d; // кол-во дней

                                        $dif = $i . ' мин';
                                        if ($h > 0)
                                            $dif = $h . ' ч';
                                        if ($d > 0)
                                            $dif = $d . ' д';
                                        ?>
                                        <li><!-- start message -->
                                            <a href="update-project?id=<?= $mes->object_id ?>#helpdesk">
                                                <h4>
                                                    <?= $mes->user->user_firstname ?>
                                                    <small><i class="fa fa-clock-o"></i> <?= $dif ?></small>
                                                </h4>
                                                <p><?= $mes->content ?></p>
                                            </a>
                                        </li>
                                        <?php
                                    }
                                }
                                ?>
                            </ul>
                        </li>
                        <li class="footer"></li>
                    </ul>
                </li>
                <li class="dropdown messages-menu">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                        <i class="fa fa-user"></i>
                        <span class="label label-warning"><?= count($profs) ?></span>
                    </a>
                    <ul class="dropdown-menu">
                        <li class="header">У Вас <?= count($profs) ?> повідомлень</li>
                        <li>
                            <ul class="menu">
                                <?php
                                if ($profs != null) {
                                    foreach ($profs as $mes) {

                                        $now = new DateTime(); // текущее время на сервере
                                        $date = DateTime::createFromFormat("Y-m-d H:i:s", $mes->d_date); // задаем дату в любом формате
                                        $interval = $now->diff($date); // получаем разницу в виде объекта DateInterval
                                        $i = $interval->i; // кол-во лет
                                        $h = $interval->h; // кол-во лет
                                        $d = $interval->d; // кол-во дней

                                        $dif = $i . ' мин';
                                        if ($h > 0)
                                            $dif = $h . ' ч';
                                        if ($d > 0)
                                            $dif = $d . ' д';
                                        ?>
                                        <li><!-- start message -->
                                            <?php if (Yii::$app->user->identity->role > 1) { ?>
                                                <a href="update-user?id=<?= $mes->object_id ?>#helpdesk">
                                                <?php } else { ?>
                                                    <a href="/cabinet/profile#helpdesk">
                                                    <?php } ?>
                                                    <h4>
                                                        <?= $mes->user->user_firstname ?>
                                                        <small><i class="fa fa-clock-o"></i> <?= $dif ?></small>
                                                    </h4>
                                                    <p><?= $mes->content ?></p>
                                                </a>
                                        </li>
                                        <?php
                                    }
                                }
                                ?>
                            </ul>
                        </li>
                        <li class="footer"></li>
                    </ul>
                </li>
                <!-- Tasks: style can be found in dropdown.less -->
                <li class="dropdown messages-menu">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                        <i class="fa fa-paper-plane"></i>
                        <span class="label label-danger"><?= count($projs) ?></span>
                    </a>
                    <ul class="dropdown-menu">
                        <li class="header">У Вас <?= count($projs) ?> Не активованих проектів </li>
                        <li>

                            <ul class="menu">
                                <?php
                                if ($projs != null) {
                                    foreach ($projs as $mes) {

                                        $now = new DateTime(); // текущее время на сервере
                                        $date = DateTime::createFromFormat("Y-m-d H:i:s", $mes->p_date); // задаем дату в любом формате
                                        $interval = $now->diff($date); // получаем разницу в виде объекта DateInterval
                                        $i = $interval->i; // кол-во лет
                                        $h = $interval->h; // кол-во лет
                                        $d = $interval->d; // кол-во дней

                                        $dif = $i . ' мин';
                                        if ($h > 0)
                                            $dif = $h . ' ч';
                                        if ($d > 0)
                                            $dif = $d . ' д';
                                        ?>
                                        <li><!-- start message -->
                                            <a href="update-project?id=<?= $mes->tree_id ?>">
                                                <h4>
                                                    <?= $mes->nname ?>
                                                    <small><i class="fa fa-clock-o"></i> <?= $dif ?></small>
                                                </h4>
                                                <p><?= $mes->user->user_firstname ?></p>
                                            </a>
                                        </li>
                                        <?php
                                    }
                                }
                                ?>
                            </ul>

                        </li>
                        <li class="footer">

                        </li>
                    </ul>
                </li>
                <!-- User Account: style can be found in dropdown.less -->

                <li class="dropdown user user-menu">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">

                        <span class="hidden-xs"><?= Yii::$app->user->identity->username ?></span>
                    </a>
                    <ul class="dropdown-menu">
                        <!-- User image -->

                        <!-- 
                        <li class="user-body">
                            <div class="col-xs-4 text-center">
                                <a href="#">Followers</a>
                            </div>
                            <div class="col-xs-4 text-center">
                                <a href="#">Sales</a>
                            </div>
                            <div class="col-xs-4 text-center">
                                <a href="#">Friends</a>
                            </div>
                        </li>Menu Body -->
                        <!-- Menu Footer-->
                        <li class="user-footer">

                            <div class="pull-right">
                                <?=
                                Html::a(
                                        'Вихід', ['/site/logout'], ['data-method' => 'post', 'class' => 'btn btn-default btn-flat']
                                )
                                ?>
                            </div>
                        </li>
                    </ul>
                </li>

                <!-- User Account: style can be found in dropdown.less -->
                <li>
                   
                   <?= Html::a('Виход',
                        ['/site/logout'],
                        ['class' => 'fa fa-sign-out', 'data-method'=>'post']); ?>
                </li>
                 <li>
                     <a href="#" id="tog-right" data-toggle="control-sidebar" style="background-color: #5C0B11; " ><i class="fa fa-gears"></i></a>
                </li>
            </ul>
        </div>
    </nav>
</header>
