<?php

namespace frontend\modules\cabinet\models;
use common\models\User;

use Yii;

/**
 * This is the model class for table "conf_users".
 *
 * @property integer $c_id
 * @property integer $u_id
 * @property string $cu_seen
 *
 * @property ConfList $c
 * @property User $u
 */
class ConfUsers extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'conf_users';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['c_id', 'u_id'], 'required'],
            [['c_id', 'u_id'], 'integer'],
            [['cu_seen'], 'safe'],
            [['c_id'], 'exist', 'skipOnError' => true, 'targetClass' => ConfList::className(), 'targetAttribute' => ['c_id' => 'cf_id']],
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
            'u_id' => 'U ID',
            'cu_seen' => 'Cu Seen',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getC()
    {
        return $this->hasOne(ConfList::className(), ['cf_id' => 'c_id']);
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
