<?php
use yii\helpers\Html;
use frontend\modules\cabinet\models\ConfList;
use frontend\modules\cabinet\models\ConfUsers;
use kartik\dropdown\DropdownX;

use yii\bootstrap\Nav;

$curr_conf = ConfList::find()->where(['cf_id' => $cf_id])->one();

/* ИЩЕМ ВСЕХ СОБЕСЕДНИКОВ С ИМЕНАМИ И ID*/
    $curr_conf_users = ConfUsers::find()->
                    joinWith('u')->
                    where(['c_id' => $curr_conf->cf_id])->
                    andWhere('u_id != ' . Yii::$app->user->id . ' ')->all();

       /* РИСУЕМ ВИДЖЕТ ВЫПАДАЮЩЕГО СПИСКА, КОТОРЫЙ ВЫВЕДЕМ В ГОЛОВЕ ОКНА ЧАТА*/
    if ($curr_conf_users != NULL) {
        $cu_data = [];
        foreach ($curr_conf_users as $curr_conf_user) {
            $cu_data[] = ['label' => $curr_conf_user->u->user_firstname,
                          'items' => [
                              ['label' => 'Диалог', 'url' => '/cabinet/chat/conf-main?u_id='.$curr_conf_user->u_id],
                              ['label' => 'Профиль', 'url' => '/cabinet/usr/update-user?id='.$curr_conf_user->u_id],
                              ]
                    ];
        }
        
        $conf_icon .= Html::beginTag('div', ['class' => 'dropdown',
            'style' => 'display: inline-block;'           
            ]);       
        $conf_icon .= Html::button('<i class="fa fa-users" style="margin-right: 5px;"></i>'.$curr_conf->cf_name.' <span class="caret"></span></button>', 
                ['type' => 'button', 'class' => 'btn btn-default chat_usr_icon', 'data-toggle' => 'dropdown',
                    'style' => 'background: none;']);
        $conf_icon .= DropdownX::widget([
            'id'=> 'dduser',
            'items' => $cu_data,
        ]);
        $conf_icon .= Html::endTag('div');
    }

    /* ВЫВОД ДИАЛОГА УДАЛЕНИЯ КОНФЕРЕНЦИИ */
    /*
      echo Dialog::widget([
      'options' => [
      'title' => 'Удалить конференцию',
      'btnOKLabel' => 'Удалить',
      'btnCancelLabel' => 'Отмена'
      ]
      ]);

      $del_button = <<< JS

      $("#btn-c-del").on("click", function() {
      krajeeDialog.confirm("Удалить данную конференцию?", function (result) {
      if (result) {
      return location.href = '/cabinet/chat/conf-del?cf_id=$cf_id';
      } else {
      return null;
      }
      });
      });

      JS;
      $this->registerJs($del_button);
     */

    echo $conf_icon;
    /*
    echo Nav::widget([
    'items' => [
        [
            'label' => 'Home',
            'url' => ['site/index'],
            'linkOptions' => [],
        ],
        [
            'label' => 'Dropdown',
            'items' => [
                 [
                     'label' => 'Level 1 - Dropdown A',
                     'items' => 
                     [
                         'label' => 'Level 2 - Dropdown B',
                         'url' => '#'
                         ],
                     [
                         'label' => 'Level 2 - Dropdown B',
                         'url' => '#'
                         ]
                     ],
                 ['label' => 'Level 1 - Dropdown B', 'url' => '#'],
            ],
        ],
        [
            'label' => 'Login',
            'url' => ['site/login'],
            'visible' => Yii::$app->user->isGuest
        ],
    ],
    'options' => ['class' =>'nav-pills'], // set this to nav-tab to get tab-styled navigation
]);
     * 
     */