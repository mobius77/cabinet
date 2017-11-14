<?php

namespace frontend\modules\cabinet\models;

use Yii;

/**
 * This is the model class for table "user_groups".
 *
 * @property integer $ug_id
 * @property string $ug_name
 * @property integer $ug_skidka
 */
class UserGroups extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'user_groups';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['ug_skidka','ug_price'], 'integer'],
            [['ug_name'], 'string', 'max' => 250],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'ug_id' => 'Ug ID',
            'ug_name' => 'Ug Name',
            'ug_skidka' => 'Ug Skidka',
            'ug_price' => 'ug_price',
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
