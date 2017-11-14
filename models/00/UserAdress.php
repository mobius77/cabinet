<?php

namespace frontend\modules\cabinet\models;

use common\models\User;

use Yii;

/**
 * This is the model class for table "user_adress".
 *
 * @property integer $a_id
 * @property string $a_city
 * @property string $a_adr
 * @property string $a_note
 * @property integer $c_id
 * @property integer $u_id
 *
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
            [['c_id', 'u_id'], 'required'],
            [['c_id', 'u_id'], 'integer'],
            [['a_city', 'a_adr'], 'string', 'max' => 250],
            [['c_id'], 'exist', 'skipOnError' => true, 'targetClass' => UserContacts::className(), 'targetAttribute' => ['c_id' => 'c_id']],
            [['u_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['u_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'a_id' => 'A ID',
            'a_city' => 'A City',
            'a_adr' => 'A Adr',
            'a_note' => 'A Note',
            'c_id' => 'C ID',
            'u_id' => 'U ID',
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
