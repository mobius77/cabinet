<section class="content-header" >
    <h4>
        Редактировать адрес
    </h4>
    <ol class="breadcrumb" style="font-size: 14px;">
        <li><a href="/cabinet"><i class="fa fa-home"></i> Главная</a></li>
<li><a href="/cabinet/usr/usr-contacts">Адреса</a></li>        
        <li class="active">Редактировать адрес</li>
    </ol>
</section>

<section class="content">
<div class="row">
    <div class="col-lg-12">
     
<?= $this->render('form_adress', [
        'obj_id' => $obj_id, 'formm' => $formm, 'model' => $model, 'way' => $way, 'act'=>'upd'
    ]) ?>   
</div>
</div>
</section>

