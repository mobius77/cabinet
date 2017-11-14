<?php
namespace frontend\modules\cabinet\models;

use Yii;
use yii\base\Model;
use common\models\User;
use frontend\modules\cabinet\models\UserContacts;

/**
 * Login form
 */
class UserForm extends Model
{
    public $user_firstname;
    public $id;    
    public $password;
    public $role, $status;    
    public $user_city, $user_pasport, $user_doc, $user_company, $user_tel, $user_adress_1;
    public $gender;
    public $user_type;
    

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            // username and password are both required
            [['user_firstname'], 'required'],
            
            [['user_city', 'user_pasport', 'user_doc', 'user_company'], 'string', 'max' => 255],
            
            [['user_firstname', 'user_adress_1'], 'string', 'max' => 250],

            ['user_firstname', 'filter', 'filter' => 'trim'],
            ['user_firstname','safe'],
            [['user_tel'], 'string', 'max' => 200],
           
            
            [['user_city', 'user_pasport', 'user_doc', 'user_company','status'],'safe'],
          
            ['password', 'required'],
            ['password', 'string', 'min' => 6],
            ['status', 'integer'],
            ['gender', 'integer'],
            ['user_type', 'safe']
           
            
            

        ];
    }

    
        /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
                       
            'user_pasport' => Yii::t('main', 'ЕДРПОУ / ИНН'),            
            'user_adress_1' => Yii::t('main', 'Полное название предприятия'),             
            'user_firstname' => Yii::t('main', 'Имя (название предприятия)'),            
            'password' => Yii::t('main', 'Пароль'),            
            'status' => Yii::t('main', 'Статус'),            
            'role' => Yii::t('main', 'Роль'),            
            'gender' => Yii::t('main', 'Группа'),
            'user_type' => Yii::t('main', 'Тип клиента'),

        ];
    }
    
    public function save()
    {
        
        
        
         if ($this->validate()) {
           
            $user = User::findOne($this->id);            
            $user->user_firstname = $this->user_firstname;  
            $user->user_pasport = $this->user_pasport;
            $user->user_adress_1 = $this->user_adress_1;
            $user->user_type = $this->user_type;
            
           if ($this->gender != '') {
                $user->gender = $this->gender;
            }
            
            
            if ($this->status!='') 
            {
                $old_status = $user->status;
                $user->status = $this->status;           
            }
            
            
            if ($this->password!=md5('captain_teemo_on_duty'))
            {
                $user->setPassword($this->password);
                $user->generateAuthKey();
            }
         
     
            if ($user->save()) {
          
                if (($old_status!=10)&&($user->status==10))
                {
                
                    /*
                 \Yii::$app->mailer->compose('notify', ['user' => $user,'message'=>$mes, 'message_extra' => $mes_extra])
                    ->setFrom([\Yii::$app->params['supportEmail'] => \Yii::$app->name .' '. Yii::t('main', 'робот')])
                    ->setTo($this->username)
                    ->setSubject('Notify ' . \Yii::$app->name)
                    ->send();
                 */
           
               /*  $mes = Yii::t('main', 'Дата та час внесення змін').': '.date("Y-m-d H:i:s");*/
                 
                 /*
                 $mes_extra = Yii::t('main', ', Ви можете додавати нові проекти.');
                 \Yii::$app->mailer->compose('notify_admin', ['user' => $usern,'message'=>$mes, 'message_extra' => $mes_extra])
                    ->setFrom(['investment_site@zoda.gov.ua'=>'investment.zoda.gov.ua'])
                    ->setTo($user->username)
                    ->setSubject('Notify ' . \Yii::$app->name)
                    ->send();
                 */
                 
                 
                }
                
            }
          /*  print_r($user->getErrors());*/
            return $user;
        }

        return null;
    }

}
