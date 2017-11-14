<?php
namespace frontend\modules\cabinet\models;

use Yii;
use yii\base\Model;
use common\models\User;
use frontend\modules\cabinet\models\OrdStatus;

/**
 * Login form
 */
class OrdStatusForm extends Model
{
    public $s_name;
    public $s_id;    
    public $s_color;
    
    

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            // username and password are both required
            [['s_name'], 'required'],
            [['s_color'], 'required'],  
            [['s_name', 's_color'], 'string', 'max' => 250],
            ['s_name', 'filter', 'filter' => 'trim'],
            ['s_id', 'integer'],

        ];
    }

    
        /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
                       
            's_name' => 'Наименование',            
            's_color' => 'Цвет',

        ];
    }
    
    public function save()
    {
         if ($this->validate()) {
            
            $stat = OrdStatus::findOne($this->s_id);            
            $stat->s_name = $this->s_name;  
            $stat->s_color = $this->s_color;
            
            if ($stat->save()) {
                
               
                
            }
            
            return $stat;
        }

        return null;
    }
    
     public function add()
    {
         if ($this->validate()) {
             
            $stat = new OrdStatus;
            
            $stat->s_name = $this->s_name;
            $stat->s_color = $this->s_color;
            
            if ( $stat->save() ) {
                return true;
            }
            
        }

        return null;
    }
    

}
