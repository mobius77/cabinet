<section class="content-header" >
    <h4>
        Добавить контакт
    </h4>
    <ol class="breadcrumb" style="font-size: 14px;">
        <li><a href="/cabinet"><i class="fa fa-home"></i> Главная</a></li> 
        <li><a href="/cabinet/usr/usr-contacts">Контакты</a></li>
        <li class="active">Добавить контакт</li>
    </ol>
</section>

<?= $this->render('form_contact', [
        'u_id' => $u_id, 'formm' => $formm, 'model' => $model, 'act'=>'add'
    ]) ?>
