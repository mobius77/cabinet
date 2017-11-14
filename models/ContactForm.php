<?php
namespace frontend\modules\cabinet\models;

use Yii;
use yii\base\Model;
use common\models\User;
use frontend\modules\cabinet\models\UserContacts;

/**
 * Contact form
 */
class ContactForm extends Model
{
    
    public $c_id;
    public $c_name, $c_email, $c_phone, $c_post, $c_note;
    public $u_id;
    public $a_flag;
    public $c_famil;
    public $c_otch;
    public $c_type;
    public $c_edr;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['c_note'], 'string'],           
            [['u_id'], 'integer'],
            [['c_id'], 'integer'],
            [['c_name', 'c_email', 'c_phone', 'c_post', 'c_famil', 'c_otch', 'c_edr'], 'string', 'max' => 250],
            [['a_flag', 'c_type'], 'integer'],
            [['c_name', 'c_phone'], 'required'],
            ['c_email', 'email'],
            [['c_famil', 'c_otch'], 'required', 'when' => function($model) {
                return $model->c_type == '0';
            }, 'whenClient' => "function (attribute, value) {
                    return $('.bootstrap-switch-id-contactform-c_type').hasClass('bootstrap-switch-off');
                }"],
            
        ];
    }

    
    
        /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'c_id' => 'C ID',
            'c_famil' => Yii::t('main', 'Фамилия'),
            'c_name' => Yii::t('main', 'Имя'),
            'c_otch' => Yii::t('main', 'Отчество'),
            'c_email' => 'E-mail',
            'c_phone' => 'Телефон',
            'c_post' => Yii::t('main', 'Должность'),
            'c_note' => Yii::t('main', 'Примечания'),
            'u_id' => 'U ID',
            'a_flag' => Yii::t('main', 'Основной').'?',
            'c_type' => Yii::t('main', 'Тип'),
            'c_edr' => Yii::t('main', 'ЕГРПОУ'),
        ];
    }
    
    public function save()
    {
         if ($this->validate()) {
            
            $user_c = UserContacts::find()->where(['c_id' => $this->c_id])->one();
            
            if ($user_c == NULL) {
                $user_c = new UserContacts;
            }
            
            if ($this->c_type > 0) {
                $user_c->c_edr = $this->c_edr;
            }
            else {
                $user_c->c_edr = null;
            }
            
            $user_c->u_id = $this->u_id;
            $user_c->c_name = $this->c_name;
            $user_c->c_famil = $this->c_famil;
            $user_c->c_otch = $this->c_otch;
            $user_c->c_email = $this->c_email;
            $user_c->c_phone = $this->c_phone;
            $user_c->c_post = $this->c_post;
            $user_c->c_type = $this->c_type;
            
            $user_c->c_note = $this->c_note;            
            $flag_old = $user_c->a_flag;            
            $user_c->a_flag = $this->a_flag;                       
            
            if ($user_c->save()) {                
                if ($flag_old != 1 && $this->a_flag == 1) {
                    $old_cont = UserContacts::find()->where(['u_id' => $user_c->u_id])->
                                andWhere('c_id != '.$user_c->c_id.'')->
                                andWhere(['a_flag' => 1])->one();
                    if ($old_cont != NULL) {
                        $old_cont->a_flag = 0;
                        $old_cont->save();
                    }
                    
                }                
            }
            
            
            return $user_c;
        }

        return null;
    }
    
    public function add()
    {
         if ($this->validate()) {
           
            $user_c = new UserContacts;
            
            if ($this->c_type > 0) {
                $user_c->c_edr = $this->c_edr;
            }
            else {
                $user_c->c_edr = null;
            }
            
            $user_c->u_id = $this->u_id;
   
            $user_c->c_name = $this->c_name;
            $user_c->c_famil = $this->c_famil;
            $user_c->c_otch = $this->c_otch;
            $user_c->c_email = $this->c_email;
            $user_c->c_phone = $this->c_phone;
            $user_c->c_post = $this->c_post;
            $user_c->c_type = $this->c_type;
            
            $user_c->c_note = $this->c_note;
            $user_c->a_flag = 0;
            
            $user_c->save();
            
            return $user_c->c_id;
        }

        return null;
    }

}
