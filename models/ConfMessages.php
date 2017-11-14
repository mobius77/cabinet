<?php

namespace frontend\modules\cabinet\models;

use common\models\User;

use Yii;

/**
 * This is the model class for table "conf_messages".
 *
 * @property integer $cm_id
 * @property integer $otp_id
 * @property integer $cf_id
 * @property string $cm_text
 * @property string $cm_date
 * @property string $cm_subj
 * @property integer $cm_status
 *
 * @property ConfList $cf
 * @property User $otp
 */
class ConfMessages extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'conf_messages';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['otp_id', 'cf_id', 'cm_text'], 'required'],
            [['otp_id', 'cf_id', 'cm_status'], 'integer'],
            [['cm_text'], 'string'],
            [['cm_date'], 'safe'],
            [['cm_subj'], 'string', 'max' => 100],
            [['cf_id'], 'exist', 'skipOnError' => true, 'targetClass' => ConfList::className(), 'targetAttribute' => ['cf_id' => 'cf_id']],
            [['otp_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['otp_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'cm_id' => 'Cm ID',
            'otp_id' => 'Otp ID',
            'cf_id' => 'Cf ID',
            'cm_text' => 'Cm Text',
            'cm_date' => 'Cm Date',
            'cm_subj' => 'Cm Subj',
            'cm_status' => 'Cm Status',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCf()
    {
        return $this->hasOne(ConfList::className(), ['cf_id' => 'cf_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOtp()
    {
        return $this->hasOne(User::className(), ['id' => 'otp_id']);
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
