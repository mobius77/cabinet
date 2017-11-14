<?php

namespace frontend\modules\cabinet\models;

use Yii;
use yii\base\Model;
use common\models\User;
use frontend\modules\cabinet\models\ConfMessages;
use frontend\modules\cabinet\models\ConfUsers;
use frontend\modules\cabinet\models\ConfList;
use yiicod\socketio\Broadcast;

/**
 * Login form
 */
class ConfMainForm extends Model {

    public $otp_id;
    public $cf_id;
    public $cm_text;
    public $cm_subj;
    public $cm_status;   
    public $cm_date;
    public $cm_id;

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            // username and password are both required
            [['cm_text'], 'required'],
            [['otp_id', 'cf_id', 'cm_status'], 'integer'],
            [['cm_text'], 'string', 'max' => 500],
            [['cm_subj'], 'string', 'max' => 200],            
            [['cm_date'], 'safe'],
            [['cm_id'], 'integer'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'cm_text' => 'Текст сообщения',
        ];
    }

    public function add() {
        if ($this->validate()) {

            $new_mes = new ConfMessages();

            $new_mes->otp_id = Yii::$app->user->id;
            $new_mes->cf_id = $this->cf_id;
            $new_mes->cm_text = $this->cm_text;
            $new_mes->cm_subj = 'Conf message';
            $new_mes->cm_status = 1;
            $new_mes->cm_date = date("Y-m-d H:i:s");

            if ($new_mes->save()) {
                
                /* ДЕЛАЕМ ОТМЕТКУ О ДАТЕ-ВРЕМЕНИ ПОСЛЕДНЕГО СООБЩЕНИЯ + УЗНАЕМ ТИП КОНФЕРЕНЦИИ ПО ФЛАГУ*/
                
                $time_mark = ConfList::find()->
                        where(['cf_id' => $new_mes->cf_id])->
                        one();
                
                $conf_type = intval($time_mark->cf_flag);
                
                $time_mark->cf_last_msg = $new_mes->cm_date;
                
                $time_mark->save();

                /* при успешном сохранении в базу рассылаем всем участникам конференции сообщение через сокет */
                $adresat_all = ConfUsers::find()
                        ->where(['c_id' => $new_mes->cf_id])
                        ->all();
                if ($adresat_all != null) {


                    $author = User::find()->where(['id' => $new_mes->otp_id])->one();
                    /* if ($author->id == Yii::$app->user->id) {
                      $msg_class = 'my_c_msg';
                      } else {
                      $msg_class = 'oth_c_msg';
                      } */
                    $msg_class = 'sssccc';

                    $path = \Yii::getAlias('@app/../frontend/modules/cabinet/views/chat') . '/msg_block.php';

                    $data['text'] = /* $path; */
                            \Yii::$app->view->renderFile($path, ['mes_one' => $new_mes, 'author' => $author, 'msg_class' => $msg_class]);

                    /*    Yii::$app->controller->renderPartial('\frontend\modules\cabinet\views\chat\msg_block', ['mes_one' => $new_mes, 'author'=>$author , 'msg_class'=>$msg_class]);  */

                    /* ОПРЕДЕЛЯЕМ КОМАНДУ ДЛЯ ОБНОВЛЕНИЯ БОКОВЫХ ВИДЖЕТОВ ПО ФЛАГУ КОНФЕРЕНЦИИ*/
                    
                    if ($conf_type > 0) {
                        $cmd = 'addconf';
                    } else {
                        $cmd = 'addchat';
                    }

                    foreach ($adresat_all as $adresat_one) {

                        if ($adresat_one->u_id == Yii::$app->user->id) {
                            $msg_class = 'my_c_msg';
                        } else {
                            $msg_class = 'oth_c_msg';
                        }
                        $dtext = str_replace('sssccc', $msg_class, $data['text']);
                        Broadcast::emitroom('message', ['otp_id' => Yii::$app->user->id, 'cm_id' => $new_mes->cm_id,
                            'cf_id' => $new_mes->cf_id,
                            'text' => $dtext,
                            'cmd' => $cmd,
                            'push_author' => $author->user_firstname,
                            'push_txt' => $this->cm_text,
                            'push_cf_name' => $time_mark->cf_name,                            
                             ], $adresat_one->u_id);
                    }
                }



                return true;
            }
            return false;
        }

        return null;
    }

    public function update() {
        if ($this->validate()) {

            $new_mes = new ConfMessages;

            $new_mes->otp_id = Yii::$app->user->id;
            $new_mes->cf_id = $this->cf_id;
            $new_mes->cm_text = $this->cm_text;
            $new_mes->cm_subj = 'Conf message';
            $new_mes->cm_status = 1;
            $new_mes->cm_date = date("Y-m-d H:i:s");

            if ($new_mes->save()) {

                return true;
            }
        }

        return null;
    }

    public function edit($curr_mes) {
        
    /*    $curr_mes = ConfMessages::find()->where(['cm_id' => $this->cm_id])->one();
        
        $curr_mes->cm_text = $this->cm_text;
        $curr_mes->cm_date = $this->cm_date;
        
        $curr_mes->otp_id = Yii::$app->user->id;
        $curr_mes->cf_id = $this->cf_id;       
        $curr_mes->cm_subj = 'Conf message';
        $curr_mes->cm_status = 1;       
        
        
        echo $this->cm_text;*/
        
        if ($curr_mes->save()) {

            $adresat_all = ConfUsers::find()
                    ->where(['c_id' => $curr_mes->cf_id])
                    ->all();
            
            if ($adresat_all != null) {                

                foreach ($adresat_all as $adresat_one) {
                    Broadcast::emitroom('message', [
                                        'otp_id' => $curr_mes->otp_id,
                                        'cm_id' => $curr_mes->cm_id,
                                        'cf_id' => $curr_mes->cf_id,
                                        'text' => $curr_mes->cm_text,
                                        'cmd' => 'edit'
                                        ],
                                        $adresat_one->u_id
                            );
                }
            }
            
            return true;
            
        } else {
            return false;
        }
    }

}
