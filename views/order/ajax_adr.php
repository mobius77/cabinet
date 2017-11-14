<?php
use frontend\modules\cabinet\models\UserContacts;
use frontend\modules\cabinet\models\UserAdress;
use frontend\modules\cabinet\models\OrdStatus;

$c = UserContacts::findOne($c_id);
$a = UserAdress::findOne($a_id);
$o_status = OrdStatus::findOne($order->order_status);

?>
  
<div class="col-lg-4 col-md-6">
<table  style="margin-bottom: 20px; width:100%; border: solid 1px #eee; " cellspacing=5 class="table-striped dataTable table-bordered table" >
    <tr>
        <th>Статус:</th>
        <td> <?= $o_status->s_name ?></td>
    </tr>
    <tr>
        <th>№ декларации:</th>
        <td> <?= $order->order_decnum ?></td>
    </tr>    
</table>            
    </div> 

<?php if($c!==null) { ?>
<div class="col-lg-4 col-md-6">
<div class="info-box">
            <span class="info-box-icon bg-yellow"><i class="fa fa-user"></i></span>

            <div class="info-box-content">
              <span class="info-box-number">Контактное лицо:</span>
              <span class="info-box-text1"><?= $c->c_famil.' '.$c->c_name.' '.$c->c_otch  ?></span><br>
              <span class="info-box-text1"><?= 'Телефон: '.$c->c_phone  ?></span>
              <span class="info-box-text1"><?= $c->c_email!='' ? ' ('.$c->c_email.')' : '' ?></span><br>
              <span class="info-box-text1"><?= $c->c_type==1 ? 'ОКПО: '.$c->c_edr : '' ?></span>
            </div>
            <!-- /.info-box-content -->
          </div>
     </div>
<?php } ?>

  
<?php if($a!==null) { ?>
    <div class="col-lg-4 col-md-6">
<div class="info-box">
            <span class="info-box-icon bg-green"><i class="fa fa-map-marker "></i></span>

            <div class="info-box-content">
              <span class="info-box-number">Адрес доставки:</span>
              <span class="info-box-text1"><?= $a->aCity->g_c_name  ?></span>
              <span class="info-box-obl_rai"> (<?= $a->aCity->g_o_name.' '.$a->aCity->g_r_name  ?>)</span><br>
              <span class="info-box-text1"><?= '<strong>'.$a->d->d_name.'</strong> № Отделения: <strong>'.$a->a_adr ?></strong></span><br>
              <span class="info-box-text1"><?= 'Примечание: '.$a->a_note ?></span>
            </div>
            <!-- /.info-box-content -->
          </div>
         </div>
<?php } ?>
     