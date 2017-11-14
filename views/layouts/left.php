<aside class="main-sidebar">

    <section class="sidebar">

        <!-- Sidebar user panel 
        <div class="user-panel">
            <div class="pull-left image">
                <img src="<?= $directoryAsset ?>/img/user2-160x160.jpg" class="img-circle" alt="User Image"/>
            </div>
            <div class="pull-left info">
                <p>Вася Пупкин</p>

                <a href="#"><i class="fa fa-circle text-success"></i> Online</a>
            </div>
        </div>-->

        <?php
        
        $items = [];
        
        array_push($items,  ['label' => 'Меню', 'options' => ['class' => 'header']]);
      if (Yii::$app->authManager->getAssignment('admin', Yii::$app->user->getId())) {  array_push($items,  ['label' => 'Главная', 'icon' => 'home', 'url' => ['/cabinet/']]); }
        
       
        array_push($items,  ['label' => 'Мой профиль', 'icon' => 'user', 'url' => ['/cabinet/usr/profile']]); 

        
        if (Yii::$app->authManager->getAssignment('admin', Yii::$app->user->getId())||Yii::$app->authManager->getAssignment('manager', Yii::$app->user->getId())) {
            array_push($items,['label' => 'Все пользователи', 'icon' => 'users', 'url' => ['/cabinet/usr/users'],]);
            array_push($items,  ['label' => 'Параметры', 'icon' => 'cogs','url' => '#', 
            'items' => [
                            ['label' => 'Статусы заказов', 'icon' => 'cart-plus', 'url' => ['/cabinet/prop/ord-status'],],
                            ['label' => 'Группы клиентов', 'icon' => 'universal-access', 'url' => ['/cabinet/prop/usr-groups'],],
                            ['label' => 'Скидки', 'icon' => 'money', 'url' => ['/cabinet/prop/discount'],],
                        ] ]);
        }
        
        if(Yii::$app->user->can('permit')) {
            array_push($items,  ['label' => 'Управление доступом', 'icon' => 'cogs', 'url' => '#',
            'items' => [
                            ['label' => 'Роли', 'icon' => 'cart-plus', 'url' => ['/permit/access/role'],],
                            ['label' => 'Права', 'icon' => 'universal-access', 'url' => ['/permit/access/permission'],],
                            
                        ] ]);
        }
        
        array_push($items,  ['label' => 'Заказы', 'icon' => 'shopping-cart', 'url' => ['/cabinet/order/index']]);
        
            
        
        if (Yii::$app->authManager->getAssignment('user', Yii::$app->user->getId())) {        
            array_push($items,  ['label' => 'Контакты', 'icon' => 'phone-square', 'url' => ['/cabinet/usr/usr-contacts']]);
         /*   array_push($items,  ['label' => 'Адреса', 'icon' => 'map-marker', 'url' => ['/cabinet/usr/usr-adress']]);*/
        }
        
        array_push($items,  ['label' => 'На сайт', 'icon' => 'chevron-circle-left', 'url' => ['/']]);
        /*
        array_push($items,  ['label' => 'Админ', 'icon' => 'fa fa-chevron-circle-left', 'url' => ['/cabinet/usr/adm-page']]);
        */
    /*    if (Yii::$app->user->identity->role>1) array_push($items,  [
                        'label' => 'Звіти',
                        'icon' => 'fa fa-share',
                        'url' => '#',
                        'items' => [
                            ['label' => 'По проектам', 'icon' => 'fa fa-file-code-o', 'url' => ['/site/get-winners'],],
                            ['label' => 'По пользователям', 'icon' => 'fa fa-dashboard', 'url' => ['/site/streamers'],],
                        ],
                    ]);
        
        */
        
        ?>
        
        
        <?= dmstr\widgets\Menu::widget(
            [
                'options' => ['class' => 'sidebar-menu tree', 'data-widget'=> 'tree'],
                'items' => $items,
            ]
        ) ?>

    </section>

</aside>
