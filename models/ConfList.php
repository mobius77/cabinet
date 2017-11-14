<?php

namespace frontend\modules\cabinet\models;

use Yii;

/**
 * This is the model class for table "conf_list".
 *
 * @property integer $cf_id
 * @property string $cf_name
 * @property string $cf_date
 * @property integer $cf_flag
 * @property string $cf_last_msg
 *
 * @property ConfMessages[] $confMessages
 * @property ConfUsers[] $confUsers
 * @property User[] $us
 */
class ConfList extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'conf_list';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['cf_name'], 'required'],
            [['cf_date', 'cf_last_msg'], 'safe'],
            [['cf_flag'], 'integer'],
            [['cf_name'], 'string', 'max' => 80],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'cf_id' => 'Cf ID',
            'cf_name' => 'Cf Name',
            'cf_date' => 'Cf Date',
            'cf_flag' => 'Cf Flag',
            'cf_last_msg' => 'Cf Last Msg',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getConfMessages()
    {
        return $this->hasMany(ConfMessages::className(), ['cf_id' => 'cf_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getConfUsers()
    {
        return $this->hasMany(ConfUsers::className(), ['c_id' => 'cf_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUs()
    {
        return $this->hasMany(User::className(), ['id' => 'u_id'])->viaTable('conf_users', ['c_id' => 'cf_id']);
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
