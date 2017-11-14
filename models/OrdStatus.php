<?php

namespace frontend\modules\cabinet\models;

use Yii;

/**
 * This is the model class for table "ord_status".
 *
 * @property integer $s_id
 * @property string $s_name
 * @property string $s_color
 */
class OrdStatus extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'ord_status';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['s_name', 's_color'], 'required'],
            [['s_name', 's_color'], 'string', 'max' => 250],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            's_id' => 'S ID',
            's_name' => 'S Name',
            's_color' => 'S Color',
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
