<?php
namespace frontend\modules\cabinet\models;

use Yii;
use yii\base\Model;
use common\models\User;
use frontend\modules\cabinet\models\ChatCommon;

/**
 * Login form
 */
class ChatMainForm extends Model
{
    public $otp_id;
    public $pol_id;    
    public $subj;
    public $status;
    public $text;
    
    
    

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            // username and password are both required
            [['text'], 'required'],
            [['otp_id', 'pol_id', 'status'], 'integer'],           
            [['text'], 'string', 'max' => 500],
            [['subj'], 'string', 'max' => 200],

        ];
    }

    
        /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
                       
            'text' => 'Текст сообщения',  

        ];
    }
    
       
     public function add()
    {
         if ($this->validate()) {            
            
             
            $new_mes = new ChatCommon;
             
            $new_mes->otp_id = Yii::$app->user->id;
            
            if ($this->pol_id > 0) {
                $new_mes->pol_id = $this->pol_id;
            } else {            
                $new_mes->pol_id = 0;
            }
            
            $new_mes->text = $this->text;
            $new_mes->subj = 'Chat message';
            $new_mes->status = 1;
            
           
            
            if ( $new_mes->save() ) {
                return true;
            }
            
        }

        return null;
    }
    

}


