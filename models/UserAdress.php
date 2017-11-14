<?php

namespace frontend\modules\cabinet\models;

use common\models\User;
use common\models\Geography;
use Yii;

/**
 * This is the model class for table "user_adress".
 *
 * @property integer $a_id
 * @property integer $a_city
 * @property string $a_adr
 * @property string $a_note
 * @property integer $c_id
 * @property integer $u_id
 * @property integer $a_flag
 *
 * @property MOrders[] $mOrders
 * @property UserContacts $c
 * @property User $u
 */
class UserAdress extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'user_adress';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['a_note'], 'string'],
            [['a_city', 'c_id', 'u_id', 'a_flag', 'd_id'], 'required'],
            [['a_city', 'c_id', 'u_id', 'a_flag', 'd_id'], 'integer'],
            [['a_adr'], 'string', 'max' => 250],
            [['c_id'], 'exist', 'skipOnError' => true, 'targetClass' => UserContacts::className(), 'targetAttribute' => ['c_id' => 'c_id']],
            [['u_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['u_id' => 'id']],
            [['a_city'], 'exist', 'skipOnError' => true, 'targetClass' => Geography::className(), 'targetAttribute' => ['a_city' => 'g_id']], 
            [['d_id'], 'exist', 'skipOnError' => true, 'targetClass' => Delivery::className(), 'targetAttribute' => ['d_id' => 'd_id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'a_id' => 'ID',
            'a_city' =>  Yii::t('main', 'Населенный пункт'), 
            'a_adr' => Yii::t('main', 'Отделение'),
            'a_note' => Yii::t('main', 'Примечание'),
            'c_id' => 'C ID',
            'u_id' => 'U ID',
            'a_flag' => 'A Flag',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMOrders()
    {
        return $this->hasMany(MOrders::className(), ['u_adr' => 'a_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getC()
    {
        return $this->hasOne(UserContacts::className(), ['c_id' => 'c_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getU()
    {
        return $this->hasOne(User::className(), ['id' => 'u_id']);
    }

    /** 
    * @return \yii\db\ActiveQuery 
    */ 
   public function getACity() 
   { 
       return $this->hasOne(Geography::className(), ['g_id' => 'a_city']); 
   } 
    
   
    /**
    * @return \yii\db\ActiveQuery
    */
    public function getD()
    {
        return $this->hasOne(Delivery::className(), ['d_id' => 'd_id']);
    }
   
  public function getLang()
                     {
            $lang = Yii::$app->session->get('lang');
           if ($lang=='') $lang = SysLang::find()->where('lang_def=1')->one()->lang_kod;
          return $this->hasOne($this::className(), ['tree_id' => 'tree_id']
     )->where('lang_kod="'.$lang.'"');
         }
    public function getIsenable() {
               return $this->tree->is_enable;
              }

}
