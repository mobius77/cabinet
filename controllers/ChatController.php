<?php

namespace frontend\modules\cabinet\controllers;

use Yii;
use yii\web\Controller;

use common\models\User;

use yii\helpers\Json;

use frontend\modules\cabinet\models\ConfForm;
use frontend\modules\cabinet\models\ConfList;
use frontend\modules\cabinet\models\ConfMessages;
use frontend\modules\cabinet\models\ConfUsers;
use frontend\modules\cabinet\models\ConfMainForm;

class ChatController extends Controller {    
    
    /*ОТПРАВКА СООБЩЕНИЯ В КОНФЕРЕНЦИЮ (АЯКС-МЕТОД)*/
    
    public function actionSendmsg() {
        
        $cf_id = Yii::$app->request->post('cf_id');
        $cm_text = Yii::$app->request->post('cm_text');
        
        if ($cf_id == '' || $cm_text == '') { return false; }
        
        $formm = new ConfMainForm();
        $formm->cf_id = $cf_id;
        $formm->cm_text = $cm_text;

        if ($formm->add()) {
            return true;
        }
        
        return false;
    }
    
    /* СОЗДАЕМ КОНФЕРЕНЦИИ */

    public function actionNewConf() {

        $this->layout = 'main';

        $formm = new ConfForm;

        /* СОЗДАЕМ КОНФЕРЕНЦИЮ ПО КНОПКЕ "ДОБАВИТЬ ПОЛЬЗОВАТЕЛЯ" В ПРИВАТНОЙ КОНФЕРЕНЦИИ */

        if (Yii::$app->request->get('u_id') && Yii::$app->request->get('u_id') != '') {
            $u_id = Yii::$app->request->get('u_id');
            
            $formm->cf_users = [$u_id];
        }

        if ($formm->load(Yii::$app->request->post())) {

            if ($formm->add()) {
                $curr_conf = ConfList::find()->where(['cf_name' => $formm->cf_name])->
                                orderBy(['cf_id' => SORT_DESC])->one();
                return $this->redirect(['/cabinet/chat/conf-main', 'cf_id' => $curr_conf->cf_id]);
            }
        }
        return $this->render('conf_add', ['formm' => $formm]);
    }
    
    /* ДОБАВЛЯЕМ ПОЛЬЗОВАТЕЛЕЙ В СУЩЕСТВУЮЩУЮ КОНФЕРЕНЦИЮ */
    
    public function actionMoreUsers() {
        $cf_id = Yii::$app->request->get('cf_id');
        if ($cf_id == '') {return false;}
        
        $users = Yii::$app->request->get('usr_add_list');
        if (!is_array($users) || $users == '' ) {return false;}
        
        $formm = new ConfForm();
        $formm->cf_id = $cf_id;
        $formm->cf_users = $users;        
        $formm->cf_name = '1';   
        $formm->cf_date = '1';
        $formm->cf_flag = 1;        
        
        if ($formm->adduser()) {
            return Yii::$app->controller->renderAjax('usr_dropdown_widget', ['cf_id' => $cf_id]);
        }
        return false;
    }

    /* ОКНО КОНФЕРЕНЦИИ И ОТПРАВКА ТУДА СООБЩЕНИЙ */

    public function actionConfMain() {
        $this->layout = 'main';


        /* СОЗДАНИЕ ФОРМЫ ДЛЯ ПЕРЕДАЧИ СООБЩЕНИЙ И ПРОВЕРКА НА ЗАГРУЗКУ ФОРМЫ */
        $formm = new ConfMainForm;

        if ($formm->load(Yii::$app->request->post())) {

            if ($formm->add()) {
                return $this->refresh();
            }
        }

        $u_id = 0;
        $cf_id = 0;

        /* ДЕЙСТВИЕ ПРИ ОТКРЫТИИ КОНФЕРЕНЦИИ МЕЖДУ 2-МЯ ПОЛЬЗОВТЕЛЯМИ (КЛИК ПО ИМЕНИ) */
        if (Yii::$app->request->get('u_id') && Yii::$app->request->get('u_id') != '') {
            $u_id = Yii::$app->request->get('u_id');

            $conf = ConfList::find()->
                    where('cf_id in (SELECT c_id FROM conf_users WHERE (`u_id`=' . $u_id . ' OR `u_id`=' . Yii::$app->user->id . ')  GROUP BY c_id HAVING count(u_id)=2) AND  cf_flag=0')->
                    one();

            if ($conf != NULL) {
                $cf_id = $conf->cf_id;
            } else {

                $new_conf = new ConfForm;
                $new_conf->cf_name = 'Личная переписка';
                $new_conf->cf_flag = 0;
                $new_conf->cf_users = $u_id;

                if ($new_conf->add()) {
                    $conf = ConfList::find()->
                            where('cf_id in (SELECT c_id FROM conf_users WHERE (`u_id`=' . $u_id . ' OR `u_id`=' . Yii::$app->user->id . ')  GROUP BY c_id HAVING count(u_id)=2) AND  cf_flag=0')->
                            one();

                    $cf_id = $conf->cf_id;
                }
            }
        }

        /* ДЕЙСТВИЕ ПРИ ОТКРЫТИИ КОНФЕРЕНЦИИ С МНОЖЕСТВЕННМИ ПОЛЬЗОВАТЕЛЯМИ (КЛИК ПО СОЗДАННОЙ КОНФЕРЕНЦИИ) */

        if (Yii::$app->request->get('cf_id') && Yii::$app->request->get('cf_id') != '') {
            $cf_id = Yii::$app->request->get('cf_id');
        }


        return $this->render('conf_main', ['cf_id' => $cf_id, 'formm' => $formm]);
    }

    /* УДАЛЯЕМ КОНФЕРЕНЦИЮ */

    public function actionConfDel($cf_id) {
        $curr_conf = ConfList::find()->where(['cf_id' => $cf_id])->one();

        if ($curr_conf->delete()) {
            return $this->redirect(['/cabinet']);
        }
    }

    /* ПЕРЕКЛЮЧАЕМ СТАТУС ПОЛЬЗОВАТЕЛЯ */

    public function actionUsrStatus() {

        if (Yii::$app->request->post('u_stat')) {
            $u_stat = Yii::$app->request->post('u_stat');
            $curr_usr = User::find()->where(['id' => Yii::$app->user->id])->one();
            $curr_usr->user_pasport_descr = $u_stat;
            if ($curr_usr->save()) {
                return $u_stat;
            }
        }
    }

    /* РЕДАКТИРУЕМ СООБЩЕНИЯ */

    public function actionMsgEdit() {       
        $cm_id = Yii::$app->request->post('cm_id');        
        
        if ($cm_id == '') { return false; }
        
        $model = ConfMessages::find()->where(['cm_id' => $cm_id])->one();
        
        if ($model->otp_id != Yii::$app->user->id) { return false; }
        
        $cm_text = Yii::$app->request->post('cm_text');
        if ($cm_text == '') { 
            $cm_text = 'Удалено';                     
        }
        
        $model->cm_text = $cm_text.' ('.date('Y.m.d H:i:s').')';
        
        $formm = new ConfMainForm();
      /*  $formm->cm_id = $cm_id;
        $formm->otp_id = Yii::$app->user->id;
        $formm->cf_id = $model->cf_id;
        $formm->cm_text = $cm_text;        
        $formm->cm_date = $model->cm_date;
        $formm->cm_subj = $model->cm_subj;
        $formm->cm_status = $model->cm_status;*/

        if ($formm->edit($model)) {
            return true;
        }
        
        return false;
    }

    /* УДАЛЯЕМ СООБЩЕНИЯ */

    public function actionMsgDel() {        
        
        $cm_id = Yii::$app->request->post('cm_id');
        
        $model = ConfMessages::find()->where(['cm_id' => $cm_id])->one();  
        $actor = User::find()->where(['id' => Yii::$app->user->id])->one();
        
        if ($model->otp_id != Yii::$app->user->id) { return 'GTFO'; }
        
            $model->cm_text = 'Сообщение удалено пользователем '.$actor->user_firstname;
            
            if ($model->save()) {            
                return $model->cm_text;
            } else {
                return 'Error!';
            }        
    }

    /* рендерим блок с указанным ID */

    public function actionShowBlock($block_id) {
        $mes_one = ConfMessages::findOne($block_id);
        $author = User::find()->where(['id' => $mes_one->otp_id])->one();
        if ($author->id == Yii::$app->user->id) {
            $msg_class = 'my_c_msg';
        } else {
            $msg_class = 'oth_c_msg';
        }
        return $this->renderPartial('msg_block', ['mes_one' => $mes_one, 'author' => $author, 'msg_class' => $msg_class]);
    }

    public function actionGetMoreMsgs($count, $cf_id) {

        $mes_all_count = ConfMessages::find()->where('cf_id = ' . $cf_id)->count();
        $cc = 15;
        $offset = $mes_all_count - $count - $cc;
        if ($offset<0) {
            $cc = 15 + $offset;
            $offset=0;
        }
        $mes_all = ConfMessages::find()->where('cf_id = ' . $cf_id)->orderBy('cm_date ASC')->offset($offset)->limit($cc)->all();

        $html = '';
        
        if ($mes_all != NULL) {
            foreach ($mes_all as $mes_one) {
                $author = User::find()->where(['id' => $mes_one->otp_id])->one();
                if ($author->id == Yii::$app->user->id) {
                    $msg_class = 'my_c_msg';
                } else {
                    $msg_class = 'oth_c_msg';
                }

                $html .= Yii::$app->controller->renderPartial("msg_block", ['mes_one' => $mes_one, 'author' => $author, 'msg_class' => $msg_class]);
            }
        }
        
        $data['text']=$html;
        $data['count']=$cc + $count;
        
        return Json::encode($data);
    }
    
    /* ОБНОВЛЯЕМ БОКОВЫЕ ПАНЕЛИ ПРИ ПОСТУПЛЕНИИ НОВОГО СООБЩЕНИЯ */
    
    /* ПАНЕЛЬ "ЧАТ" */
    public function actionSidebarRefreshU() {
 /*ПРОВЕРЯЕМ, ОТКРЫТО ЛИ У ЮЗЕРА ОКНО КОНФЕРЕНЦИИ, В КОТОРУЮ ПРИШЛО СООБЩЕНИЕ
        ЕСЛИ ОТКРЫТО - ОБНОВЛЯЕМ ДАТУ ПРОСМТОРА ЮЗЕРОМ ЭТОЙ КОНФЕРЕНЦИИ, ЧТОБЫ СЧЕТЧИК НЕПРОЧИТАННЫХ
         СООБЩЕНИЙ РАБОТАЛ КОРРЕКТНО*/        
       $cf_id = Yii::$app->request->post('cf_id');
       $act = Yii::$app->request->post('act');
       $cmd = Yii::$app->request->post('cmd');
        
        if ($cf_id != '' && $act == 'do') {
            /*$curr_conf = ConfList::find()->where(['cf_id' => $cf_id])->one();*/            
            $visit = ConfUsers::find()->
                    where(['c_id' => $cf_id])->
                    andWhere(['u_id' => Yii::$app->user->id])->
                    one();            
            $visit->cu_seen = date("Y-m-d H:i:s");
            
            $visit->save();
        }       
        /*ПРОВЕРЯЕМ КОМАНДУ: ОБНОВИТЬ ПАНЕЛЬ ЧАТА ИЛИ КОНФЕРЕНЦИИ*/
        switch ($cmd) {
            
            /*ПАНЕЛЬ КОНФЕРЕНЦИЙ РЕНДЕРИМ ЦЕЛИКОМ*/
            case 'addconf':
                $response = Yii::$app->controller->renderAjax('confs');
                return $response;
            break;
        
            /*ПАНЕЛЬ ЧАТОВ - ВОЗВРАЩАЕМ ID ОТПРАВИТЕЛЯ СОООБЩЕНИЯ И КОЛ-ВО ПРОПУЩЕННЫХ
             * СООБЩЕНИЙ ОТ ЭТОГО ПОЛЬЗОВАТЕЛЯ */
            case 'addchat':
                /*ЕСЛИ ОКНО ЧАТА С ОТПРАВИТЕЛЕМ ОТКРЫТО - ВОЗВРАЩАЕМ НУЛЛ, Т.К. ОБНОВЛЕНИЯ ПАНЕЛИ НЕ БУДЕТ*/
                if ($act == 'do') { return null; }
                
                $response = [];
                
                $visit_date = ConfUsers::find()->
                                  where(['u_id' => Yii::$app->user->id])->
                                  andWhere(['c_id' => $cf_id])->
                                  one();
                    
                $mes_unseen = ConfMessages::find()->
                                  where(['cf_id' => $cf_id])->
                                  andWhere('cm_date > "'.$visit_date->cu_seen.'" ')->
                                  andWhere('otp_id != '.Yii::$app->user->id.' ')->
                                  count();                    
                
                $oth_user = ConfUsers::find()->
                                  where('u_id != '.Yii::$app->user->id.' ')->
                                  andWhere(['c_id' => $cf_id])->
                                  one();
                
                $response['user'] =  $oth_user->u_id;
                $response['unseen'] = $mes_unseen;
                $response = Json::encode($response);
                return $response;
            break;
            
            /*ОБНОВЛЕНИЕ ПАНЕЛИ "ЧАТ" ПРИ АКТИВАЦИИ ВКЛАДКИ*/
            case 'u_refresh':
                $active_chat = ConfUsers::find()->
                    joinWith('c')->
                    where(['c_id' => $cf_id])->
                    andWhere('u_id != '.Yii::$app->user->id.' ')->
                    andWhere(['conf_list.cf_flag' => 0])->
                    one();
                if ($active_chat != NULL) {
                    $cf_id = $active_chat->c_id;
                } else {
                    $cf_id = 0;
                }
                return Yii::$app->controller->renderAjax('users', ['cf_id' => $cf_id]);
            break;
        }
        
    }
    
    /*ПЕРЕРЕНДЕРИВАЕМ СООБЩЕНИЯ ЧАТА И ДЕЛАЕМ ОТМЕТКУ О ПРОСМОТРЕ ДАННОЙ КОНФЕРЕНЦИИ*/
    public function actionRefreshCb() {
        $cf_id = Yii::$app->request->post('cf_id');
        /*ОБНОВЛЯЕМ ДАТУ ПОСЕЩЕНИЯ*/
        $visit = ConfUsers::find()->
                    where(['c_id' => $cf_id])->
                    andWhere(['u_id' => Yii::$app->user->id])->
                    one();            
            $visit->cu_seen = date("Y-m-d H:i:s");
            
            $visit->save();
        /*РЕНДЕРИМ СООБЩЕНИЯ*/
        $mes_all_count = ConfMessages::find()->where('cf_id = ' . $cf_id)->count();
        $mes_all = ConfMessages::find()->where('cf_id = ' . $cf_id . ' AND cm_date >= "' . date('Y-m-d') . '"')->orderBy('cm_date ASC')->all();
        if ($mes_all == null) {
            $mes_all = ConfMessages::find()->where('cf_id = ' . $cf_id)->orderBy('cm_date ASC')->offset($mes_all_count - 5)->limit(5)->all();
        }
        
        return Yii::$app->controller->renderAjax('chat_body', ['mes_all' => $mes_all]);
    }
    
    
}
