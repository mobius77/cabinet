<?php

namespace frontend\modules\cabinet\models;

use common\models\User;

use Yii;

/**
 * This is the model class for table "user_contacts".
 *
 * @property integer $c_id
 * @property string $c_name
 * @property string $c_email
 * @property string $c_phone
 * @property string $c_post
 * @property string $c_note
 * @property integer $u_id
 *
 * @property UserAdress[] $userAdresses
 * @property User $u
 */
class UserContacts extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'user_contacts';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['c_note'], 'string'],
            [['u_id'], 'required'],
            [['u_id'], 'integer'],
            [['c_id'], 'integer'],
            [['c_name', 'c_email', 'c_phone', 'c_post'], 'string', 'max' => 250],
            [['u_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['u_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'c_id' => 'C ID',
            'c_name' => 'C Name',
            'c_email' => 'C Email',
            'c_phone' => 'C Phone',
            'c_post' => 'C Post',
            'c_note' => 'C Note',
            'u_id' => 'U ID',
        ];
    }

        /** 
        * @return \yii\db\ActiveQuery 
        */ 
       public function getMOrders() 
       { 
           return $this->hasMany(MOrders::className(), ['u_contact' => 'c_id']); 
       }
    
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUserAdresses()
    {
        return $this->hasMany(UserAdress::className(), ['c_id' => 'c_id']);
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
