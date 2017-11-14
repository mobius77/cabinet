<?php
namespace frontend\modules\cabinet\models;

use Yii;
use yii\base\Model;
use common\models\User;
use frontend\modules\cabinet\models\UserGroups;

/**
 * Login form
 */
class UsrGroupForm extends Model
{
    public $ug_name;
    public $ug_id;    
    public $ug_skidka;
    public $ug_price;
    

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            // username and password are both required
            [['ug_name'], 'required'],
            [['ug_skidka','ug_price'], 'required'],  
            [['ug_name'], 'string', 'max' => 250],
            ['ug_name', 'filter', 'filter' => 'trim'],
            [['ug_id', 'ug_skidka'], 'integer'],

        ];
    }

    
        /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
                       
            'ug_name' => 'Наименование',            
            'ug_skidka' => 'Скидка',
            'ug_price' => 'Тип цены',

        ];
    }
    
    public function save()
    {
         if ($this->validate()) {
            
            $stat = UserGroups::findOne($this->ug_id);            
            $stat->ug_name = $this->ug_name;  
            $stat->ug_skidka = $this->ug_skidka;
            $stat->ug_price = $this->ug_price;
            
            if ($stat->save()) {
                
               
                
            }
            
            return $stat;
        }

        return null;
    }
    
     public function add()
    {
         if ($this->validate()) {
             
            $stat = new UserGroups;
            
            $stat->ug_name = $this->ug_name;  
            $stat->ug_skidka = $this->ug_skidka;
            $stat->ug_price = $this->ug_price;
            
            if ( $stat->save() ) {
                return true;
            }
            
        }

        return null;
    }
    

}
