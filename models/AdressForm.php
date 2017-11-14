<?php
namespace frontend\modules\cabinet\models;

use Yii;
use yii\base\Model;

use frontend\modules\cabinet\models\UserAdress;
use frontend\modules\cabinet\models\UserContacts;

/**
 * Login form
 */
class AdressForm extends Model
{
    public $a_id;
    public $a_city;    
    public $a_adr;
    public $a_note;    
    public $c_id;
    public $u_id;
    public $a_flag;
    

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            // username and password are both required
           [['a_note'], 'string'],           
            [['c_id'], 'integer'],
            [['c_id'], 'required'],
            [['a_city', 'a_adr'], 'string', 'max' => 250],
            [['u_id'], 'safe'],
            [['a_id'], 'integer'],
            [['a_flag'], 'integer'],
            [['a_city', 'a_adr'], 'required'],
        ];
    }

    
        /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'a_city' => Yii::t('main', 'Населенный пункт'),            
            'a_adr' => Yii::t('main', 'Отделение'),
            'a_note' => Yii::t('main', 'Примечание'),
            'a_flag' => Yii::t('main', 'Основной').'?',

        ];
    }
    
    public function save()
    {
         if ($this->validate()) {           
            
            $user_adr = UserAdress::find()->where(['a_id' => $this->a_id])->one();
            
            if ($user_adr == NULL) {
                $user_adr = new UserAdress;
            }
            
            $user_adr->c_id = $this->c_id;
            $user_adr->u_id = $this->u_id;
            $user_adr->a_city = $this->a_city;
            $user_adr->a_adr = $this->a_adr;
            $user_adr->a_note = $this->a_note;            
           
            $flag_old = $user_adr->a_flag;
            $user_adr->a_flag = $this->a_flag;
            
            if ($this->c_id == '') {
                return false;
            } else {
                if ($user_adr->save()) {
                    if ($flag_old != 1 && $this->a_flag == 1) {
                        $old_cont = UserAdress::find()->where(['c_id' => $user_adr->c_id])->
                                    andWhere('a_id != '.$user_adr->a_id.'')->
                                    andWhere(['a_flag' => 1])->one();
                        if ($old_cont != NULL) {
                            $old_cont->a_flag = 0;
                            $old_cont->save();
                        }                    
                    } 
                    return $user_adr;    
                }
            }
            
            
        }

        return null;
    }
    
    public function add()
    {
         if ($this->validate()) {
           
            $user_cont = UserContacts::find()->where(['c_id' => $this->c_id])->one(); 
             
            $user_adr = new UserAdress;
            
            $user_adr->c_id = $this->c_id;
            $user_adr->u_id = $user_cont->u_id;
            $user_adr->a_city = $this->a_city;
            $user_adr->a_adr = $this->a_adr;
            $user_adr->a_note = $this->a_note;
            $user_adr->a_flag = 0;
            
            $user_adr->save();
            
            return $user_adr->a_id;
        }

        return null;
    }

}
