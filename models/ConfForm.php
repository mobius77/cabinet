<?php

namespace frontend\modules\cabinet\models;

use Yii;
use yii\base\Model;
use common\models\User;
use frontend\modules\cabinet\models\ConfList;
use frontend\modules\cabinet\models\ConfMessages;
use frontend\modules\cabinet\models\ConfUsers;
use frontend\modules\cabinet\models\ConfMainForm;

/**
 * Login form
 */
class ConfForm extends Model {

    public $cf_id;
    public $cf_name;
    public $cf_users;
    public $cf_date;
    public $cf_flag;

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            // username and password are both required
                [['cf_name', 'cf_users'], 'required'],
                [['cf_date'], 'safe'],
                [['cf_name'], 'string', 'max' => 80],
                [['cf_users'], 'safe'],
                [['cf_flag', 'cf_id'], 'integer'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'cf_name' => 'Название конференции',
            'cf_users' => 'Участники конференции',
        ];
    }

    public function add() {
        /* ДОБАВЛЯЕМ КОНФЕРЕНЦИЮ В СПИСОК */
        if ($this->validate()) {

            $new_conf = new ConfList;

            $new_conf->cf_name = $this->cf_name;
            $new_conf->cf_date = date("Y-m-d H:i:s");
            $new_conf->cf_last_msg = date("Y-m-d H:i:s");
            if (isset($this->cf_flag) && $this->cf_flag > 0  ) {
                $new_conf->cf_flag = $this->cf_flag;
            } else {
                $new_conf->cf_flag = 0;
            }
            

            if ($new_conf->save()) {
                /* ДОБАВЛЯЕМ СЕБЯ И ПОЛЬЗОВАТЕЛЕЙ КОНФЕРЕНЦИИ (ВИДЖЕТ SELECT2 ВОЗВРАЩАЕТ МАССИВ С ID) 
                 * ПРИ СОЗДАНИИ КОНФ. С ОТДЕЛЬНЫМ ПОЛЬЗОВАТЕЛЕМ ПРИХОДИТ ТОЛЬКО ЕГО ID БЕЗ МАССИВА
                 */

                $curr_conf = ConfList::find()->where(['cf_name' => $this->cf_name])->
                                orderBy(['cf_id' => SORT_DESC])->one();

                $new_usr = new ConfUsers;
                $new_usr->c_id = $curr_conf->cf_id;
                $new_usr->u_id = Yii::$app->user->id;
                $new_usr->cu_seen = date("Y-m-d H:i:s");
                $new_usr->save();

                if (is_array($this->cf_users)) {

                    foreach ($this->cf_users as $user) {
                        $new_usr = new ConfUsers;
                        $new_usr->c_id = $curr_conf->cf_id;
                        $new_usr->u_id = $user;
                        $new_usr->cu_seen = date("Y-m-d H:i:s");
                        $new_usr->save();
                    }
                } else {
                    $new_usr = new ConfUsers;
                    $new_usr->c_id = $curr_conf->cf_id;
                    $new_usr->u_id = $this->cf_users;
                    $new_usr->cu_seen = date("Y-m-d H:i:s");
                    $new_usr->save();
                }

                return true;
            }
        }

        return null;
    }
    
    /* ДОБАВЛЯЕМ ПОЛЬЗОВАТЕЛЕЙ В СУЩЕСТВУЮЩУЮ КОНФЕРЕНЦИЮ */
    public function adduser() {
        $username_list = [];
        foreach ($this->cf_users as $user) {
            $new_usr = new ConfUsers;
            $new_usr->c_id = $this->cf_id;
            $new_usr->u_id = $user;
            $new_usr->cu_seen = date("Y-m-d H:i:s");
            
            if ( $new_usr->save() ) {
                $username = User::find()->where(['id' => $user])->one();
                $username_list[] = $username->user_firstname;
                unset($username);
            }
        }
        
        $new_mes = new ConfMainForm();
        $new_mes->otp_id = Yii::$app->user->id;
        $new_mes->cf_id = $this->cf_id;
        $new_mes->cm_text = 'Приглашены пользователи: '.implode(",&nbsp;", $username_list);
        $new_mes->cm_subj = 'Conf message';
        $new_mes->cm_status = 1;
        $new_mes->cm_date = date("Y-m-d H:i:s");
        
        if ($new_mes->add()) {
            return true;
        }
        
        return true;
    }
    
}
