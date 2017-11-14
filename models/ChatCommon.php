<?php

namespace frontend\modules\cabinet\models;

use Yii;

/**
 * This is the model class for table "chat_common".
 *
 * @property integer $cc_id
 * @property integer $otp_id
 * @property integer $pol_id
 * @property string $text
 * @property string $cc_date
 * @property string $subj
 * @property integer $status
 */
class ChatCommon extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'chat_common';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['otp_id', 'pol_id', 'text', 'subj', 'status'], 'required'],
            [['otp_id', 'pol_id', 'status'], 'integer'],
            [['cc_date'], 'safe'],
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
            'cc_id' => 'Cc ID',
            'otp_id' => 'Otp ID',
            'pol_id' => 'Pol ID',
            'text' => 'Text',
            'cc_date' => 'Cc Date',
            'subj' => 'Subj',
            'status' => 'Status',
        ];
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
