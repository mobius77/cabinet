<?php

use kartik\tabs\TabsX;
use yii\helpers\Url;

$items = [
    [
        'label'=>'<i class="glyphicon glyphicon-user"></i> Пользователи',
        'content'=>$users_show,
        'active'=>true
    ],
    [
        'label'=>'<i class="glyphicon glyphicon-phone-alt"></i> Контакты',
        'content'=>$contacts,
        
    ],
    [
        'label'=>'<i class="glyphicon glyphicon-home"></i> Адреса',
        'content'=>$adress,
        
    ],
    
    
];

echo TabsX::widget([
    'items'=>$items,
    'position'=>TabsX::POS_ABOVE,
    'encodeLabels'=>false
]);

/*
$items = [
    [
        'label'=>'<i class="glyphicon glyphicon-home"></i> Home',
        'content'=>$content1,
        'active'=>true,
        'linkOptions'=>['data-url'=>'/usr/adm-contacts']
    ],
    
    [
        'label'=>'<i class="glyphicon glyphicon-user"></i> Profile',
        'content'=>$content2,
        'linkOptions'=>['data-url'=>Url::to(['/site/fetch-tab?tab=2'])]
    ]
];

        
echo TabsX::widget([
    'items'=>$items,
    'position'=>TabsX::POS_ABOVE,
    'encodeLabels'=>false
]);
*/
?>
