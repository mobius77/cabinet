<?php

use yii\helpers\Html;
use kartik\form\ActiveForm;
use kartik\builder\Form;
use yii\helpers\Url;
use kartik\widgets\Select2;
use kartik\tabs\TabsX;

if ($usr->status == 10) {
            $nm_color = 'text-primary';
        } else {
            $nm_color = 'text-danger';
        }

/*
  if ($model_adr == NULL) {
  $form_a = new \frontend\modules\cabinet\models\AdressForm();
  $form_a->a_city = $form_a->a_adr = $form_a->a_note = '';
  $form_a->c_id = $formm->c_id;
  }
 */
?>

<section class="content-header" style="margin-bottom: 25px;">
    <h4>
        Профиль пользователя: <span class="<?= $nm_color ?>"> <?= $usr->username ?></span>
    </h4>
    <ol class="breadcrumb" style="font-size: 14px;">
        <li><a href="/cabinet"><i class="fa fa-home"></i> Главная</a></li>
        <li><a href="/cabinet/usr/users">Все пользователи</a></li>
        <li class="active"><?= $usr->username ?></li>
    </ol>
</section>

<section class="content">
    <div class="row">
        <div class="col-lg-12">
    <div class="box box-solid" style="">
<?php

$items = [
        [
        'label' => '<i class="fa fa-user" style="margin-right: 5px;"></i> Данные пользователя',
        'content' => $upd_form,
    ],
        [
        'label' => '<i class="fa fa-phone-square" style="margin-right: 5px;"></i> Контакты',
        'content' => $contacts,
    ],
   /*     [
        'label' => '<i class="fa fa-map-marker" style="margin-right: 5px;"></i> Адреса',
        'content' => $adress,
    ],*/
];

echo TabsX::widget([
    'items' => $items,
    'position' => TabsX::POS_ABOVE,
    'encodeLabels' => false
]);
?>
    </div>
    </div>
    </div>
</section>