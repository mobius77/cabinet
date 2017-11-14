<section class="content-header" >
    <h4>
        Редактировать контакт
    </h4>
    <ol class="breadcrumb" style="font-size: 14px;">
        <li><a href="/cabinet"><i class="fa fa-home"></i> Главная</a></li> 
        <li><a href="/cabinet/usr/usr-contacts">Контакты</a></li>
        <li class="active">Редактировать контакт</li>
    </ol>
</section>


<?= $this->render('form_contact', [
        'obj_id' => $obj_id, 'formm' => $formm, 'model' => $model, 'way' => $way, 'act'=>'upd'
    ]) ?>



