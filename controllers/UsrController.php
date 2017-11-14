<?php

namespace frontend\modules\cabinet\controllers;

use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\helpers\Url;
use common\models\Dialog;
use yii\web\UploadedFile;

/**
 * Site controller
 */
class UsrController extends Controller {

    public $tree;

    /**
     * @inheritdoc
     */
    public function actions() {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
        ];
    }

    public function beforeAction($action) {
        if (parent::beforeAction($action)) {
            // change layout for error action
            if ($action->id == 'error')
                $this->layout = 'main-error';
            return true;
        } else {
            return false;
        }
    }

    /* ---------USERS---------- */

    public function actionUsers() {
        $this->layout = 'main';

        $users = \common\models\User::find()->where('')->orderBy('status, username')->all();

        return $this->render('users', [
                    'users' => $users,
        ]);
    }

    public function actionUpdateUser($id) {
        $this->layout = 'main';
        $object = 'usr';

        /* USER DATA */

        $model = $this->findUser($id);
        $formm = new \frontend\modules\cabinet\models\UserForm();
        $formm->user_firstname = $model->user_firstname;
        $formm->id = $model->id;
        $formm->user_pasport = $model->user_pasport;    /* edrpou */
        $formm->user_adress_1 = $model->user_adress_1; /* polnoe nazvanie */
        $formm->status = $model->status;
        $formm->role = $model->role;
        $formm->gender = $model->gender;
        $formm->user_type = $model->user_type;

        /* END USER DATA */

        /* CONTACTS */
        $searchModel = new \frontend\modules\cabinet\models\search\ContactSearch;
        $searchModel->u_id = $model->id;
        $dataProvider = $searchModel->search(Yii::$app->request->getQueryParams());

        $contacts = $this->renderPartial('usr_contacts', [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
            'u_id' => $id,
            'b_crumb' => 'adm'
        ]);
        /* END CONTACTS */

        /* ADRESS */

        $searchModel = new \frontend\modules\cabinet\models\search\AdressSearch;
        $searchModel->u_id = $model->id;
        $dataProvider = $searchModel->search(Yii::$app->request->getQueryParams());


        $adress = $this->renderPartial('usr_adress', [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
            'user' => $model,
            'c_id' => 0,
            'u_id' => $id,
            'b_crumb' => 'adm'
        ]);

        /* END ADRESS */



        /*
          $chat = Dialog::find()->joinWith('user')->where('object = "' . $object . '" AND object_id=' . $id)->orderBy('d_date DESC')->all();
         */


        if ($formm->load(Yii::$app->request->post())) {

            if (UploadedFile::getInstance($formm, 'user_doc') != NULL) {

                $file = UploadedFile::getInstance($formm, 'user_doc');

                $filename = $file->baseName;

                $new_filename = $filename . $formm->id . date("Ymdhis");

                $formm->user_doc = $new_filename . '.' . $file->extension;
            }

            if ($formm->save()) {

                if ($file != NULL) {
                    $file->saveAs('../../frontend/web/profile/files/' . $formm->user_doc);
                }

                $model = $this->findUser($id);

                $chat = Dialog::find()->joinWith('user')->where('object = "' . $object . '" AND object_id=' . $id)->orderBy('d_date DESC')->all();

                $upd_form = $this->renderPartial('usr_update_form', ['obj_id' => $id, 'formm' => $formm, 'model' => $model, 'chat' => $chat, 'object' => $object,
                    'b_crumb' => 'adm']);

                return $this->render('update-user', ['upd_form' => $upd_form, 'contacts' => $contacts, 'adress' => $adress, 'usr' => $model]);
            }
        } else {

            $upd_form = $this->renderPartial('usr_update_form', ['obj_id' => $id, 'formm' => $formm, 'model' => $model, 'chat' => $chat, 'object' => $object,
                'b_crumb' => 'adm']);

            Url::remember();

            return $this->render('update-user', ['upd_form' => $upd_form, 'contacts' => $contacts, 'adress' => $adress, 'usr' => $model]);
        }
    }

    /*  public function actionUpdateAdress() {

      $form_a = new \frontend\modules\cabinet\models\AdressForm();

      if ($form_a->load(Yii::$app->request->post())) {



      if ($form_a->save()) {

      return $this->redirect(['usr/users']);
      } else {
      echo 'Ничего не пришло';
      }
      }
      } */

    public function actionDeleteUser($id) {

        $model = $this->findUser($id);
        /*
          $count = \common\models\ValTree33::find()->where('user_id = '.$id)->count();
         */
        /* if ($count==0) */
        $model->delete();

        return $this->redirect(['usr/users']);
    }

    protected function findUser($id) {
        if (($model = \common\models\User::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    /* ---------------------- */

    /* -------USER CONTACTS--------- */

    public function actionUsrContacts() {
        $this->layout = 'main';

        $searchModel = new \frontend\modules\cabinet\models\search\ContactSearch;

        if (Yii::$app->request->get('u_id') && Yii::$app->request->get('u_id') != '') {
            $searchModel->u_id = Yii::$app->request->get('u_id');
        } else {
            if (Yii::$app->authManager->getAssignment('admin', Yii::$app->user->getId())) {
                $searchModel->u_id = 0;
            } else {
                $searchModel->u_id = Yii::$app->user->id;
                $u_id = Yii::$app->user->id;
            }
        }

        $dataProvider = $searchModel->search(Yii::$app->request->getQueryParams());

        Url::remember();

        return $this->render('usr_contacts', [
                    'dataProvider' => $dataProvider,
                    'searchModel' => $searchModel,
                    'u_id' => $u_id
        ]);
    }

    public function actionUpdateUsrContacts($c_id) {
        $this->layout = 'main';

        $model_c = \frontend\modules\cabinet\models\UserContacts::find()->
                where(['c_id' => $c_id])->
                one();

        $formm = new \frontend\modules\cabinet\models\ContactForm();

        if ($model_c == NULL) {
            $formm->c_name = $formm->c_email = $formm->c_phone = $formm->c_post = $formm->c_note = '';
            $formm->c_id = $model->id;
        } else {
            $formm->c_id = $model_c->c_id;
            $formm->u_id = $model_c->u_id;
            $formm->c_name = $model_c->c_name;
            $formm->c_famil = $model_c->c_famil;
            $formm->c_otch = $model_c->c_otch;
            $formm->c_email = $model_c->c_email;
            $formm->c_phone = $model_c->c_phone;
            $formm->c_post = $model_c->c_post;
            $formm->c_note = $model_c->c_note;
            $formm->c_type = $model_c->c_type;
            $formm->c_edr = $model_c->c_edr;
            $formm->a_flag = $model_c->a_flag;
        }

        if ($formm->load(Yii::$app->request->post())) {

            $formm->save();

            $model_c = \frontend\modules\cabinet\models\UserContacts::find()->
                    where(['c_id' => $c_id])->
                    one();



            return $this->render('update-contact', ['obj_id' => $c_id, 'formm' => $formm, 'model' => $model_c, 'way' => 'back']);
        } else {

            return $this->render('update-contact', ['obj_id' => $c_id, 'formm' => $formm, 'model' => $model_c, 'way' => 'canc']);
        }
    }

    public function actionAddUsrContact() {
        $this->layout = 'main';

        if (Yii::$app->authManager->getAssignment('admin', Yii::$app->user->getId())||Yii::$app->authManager->getAssignment('manager', Yii::$app->user->getId())) {
            if (Yii::$app->request->get('u_id') && Yii::$app->request->get('u_id') != '') {
                $u_id = Yii::$app->request->get('u_id');
            }
        }
        else
        {
            $u_id = Yii::$app->user->id;
        }

        $formm = new \frontend\modules\cabinet\models\ContactForm();

        if ($formm->load(Yii::$app->request->post())) {
            if ($formm->add()) {

                return $this->redirect([Url::previous()]);
            }
        }

        return $this->render('add-contact', ['u_id' => $u_id, 'formm' => $formm]);
    }

    public function actionDeleteUsrContacts($c_id) {

        $model_c = \frontend\modules\cabinet\models\UserContacts::find()->
                where(['c_id' => $c_id])->
                one();
        /*
          $model_a = \frontend\modules\cabinet\models\UserAdress::find()->
          where(['c_id' => $c_id])->
          all();
         */
        $c_id = $model_c->c_id;

        if ($model_c->delete()) {

            return $this->redirect([Url::previous()]);
        }
    }

    /* -------END USER CONTACTS--------- */


    /* ------- USER ADRESS --------------- */

    public function actionUsrAdress() {
        $this->layout = 'main';

        $searchModel = new \frontend\modules\cabinet\models\search\AdressSearch;

        if (Yii::$app->request->get('u_id')) {
            $usr = Yii::$app->request->get('u_id');
            $user = $this->findUser($usr);
            $searchModel->u_id = $user->id;
            $searchModel->c_id = 'empty';
        } else {

            $user = $this->findUser(Yii::$app->user->id);
            if (!Yii::$app->authManager->getAssignment('admin', Yii::$app->user->getId())) {
                $searchModel->u_id = $user->id;
            } else {
                $searchModel->u_id = 0;
            }

            if (Yii::$app->request->get('c_id')) {
                $searchModel->c_id = Yii::$app->request->get('c_id');
            } else {
                $searchModel->c_id = 'empty';
            }
        }

        $dataProvider = $searchModel->search(Yii::$app->request->getQueryParams());

        Url::remember();

        return $this->render('usr_adress', [
                    'dataProvider' => $dataProvider,
                    'searchModel' => $searchModel,
                    'user' => $user,
                    'c_id' => $searchModel->c_id
        ]);
    }

    public function actionUpdateUsrAdress($a_id) {
        $this->layout = 'main';

        $model_a = \frontend\modules\cabinet\models\UserAdress::find()->
                where(['a_id' => $a_id])->
                one();

        $formm = new \frontend\modules\cabinet\models\AdressForm();

        $formm->a_id = $model_a->a_id;
        $formm->c_id = $model_a->c_id;
        $formm->u_id = $model_a->u_id;
        $formm->a_city = $model_a->a_city;
        $formm->a_adr = $model_a->a_adr;
        $formm->a_note = $model_a->a_note;
        $formm->a_flag = $model_a->a_flag;

        if ($formm->load(Yii::$app->request->post())) {

            $formm->save();

            $model_a = \frontend\modules\cabinet\models\UserAdress::find()->
                    where(['a_id' => $a_id])->
                    one();

            return $this->render('update-adress', ['obj_id' => $a_id, 'formm' => $formm, 'model' => $model_a, 'way' => 'back']);
        } else {

            return $this->render('update-adress', ['obj_id' => $a_id, 'formm' => $formm, 'model' => $model_a, 'way' => 'canc']);
        }
    }

    public function actionAddUsrAdress($c_id) {
        $this->layout = 'main';

        /*    $contact = \frontend\modules\cabinet\models\UserContacts::find()->
          where(['c_id' => $c_id])->
          one(); */

        if (Yii::$app->request->get('u_id') && Yii::$app->request->get('u_id') != '') {
            $u_id = Yii::$app->request->get('u_id');
        }

        $formm = new \frontend\modules\cabinet\models\AdressForm();

        if ($formm->load(Yii::$app->request->post())) {
            if ($formm->add()) {
                return $this->redirect([Url::previous()]);
            }
        }

        return $this->render('add-adress', ['c_id' => $c_id, 'u_id' => $u_id, 'formm' => $formm]);
    }

    public function actionDeleteUsrAdress($a_id) {
        $model_a = \frontend\modules\cabinet\models\UserAdress::find()->
                where(['a_id' => $a_id])->
                one();

        if ($model_a->delete()) {
            return $this->redirect([Url::previous()]);
        }
    }

    /* ------- END USER ADRESS --------------- */

    /* -----------ADMIN INTERFACE --------------- */

    public function actionAdmPage() {
        $this->layout = 'main';

        /* USERS */

        $this->layout = 'main';

        $users = \common\models\User::find()->where('')->orderBy('status, username')->all();

        $users_show = $this->renderPartial('users', [
            'users' => $users,
        ]);

        /* END USERS */


        /* CONTACTS */
        $searchModel = new \frontend\modules\cabinet\models\search\ContactSearch;
        $searchModel->u_id = 0;
        $dataProvider = $searchModel->search(Yii::$app->request->getQueryParams());

        $contacts = $this->renderPartial('usr_contacts', [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
            'u_id' => Yii::$app->user->id,
        ]);
        /* END CONTACTS */

        /* ADRESS */

        $searchModel = new \frontend\modules\cabinet\models\search\AdressSearch;
        $searchModel->u_id = 0;
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams, 'empty');

        $c_id = 0;

        $adress = $this->renderPartial('usr_adress', [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
            'user' => $user,
            'c_id' => $c_id
        ]);

        /* END ADRESS */

        return $this->render('adm_page', ['contacts' => $contacts, 'adress' => $adress, 'users_show' => $users_show]);
    }

    public function actionContListShow() {

        $ids = $_POST['depdrop_parents'];

        $id = $ids[0];

        if ($id != '') {
            $list = \frontend\modules\cabinet\models\UserContacts::find()->
                            where('u_id=' . $id)->all();
        } else {
            $id = NULL;
        }
        $selected = null;
        if ($id != null && count($list) > 0) {
            $selected = '';
            foreach ($list as $i => $account) {
                $out[] = ['id' => $account['c_id'], 'name' => $account['c_name']];
                if ($i == 0) {
                    $selected = $account['c_id'];
                }
            }
            // Shows how you can preselect a value
            echo \yii\helpers\Json::encode(['output' => $out, 'selected' => $selected]);
            return;
        }
    }

    /* -----------END ADMIN INTERFACE --------------- */



    /* ----PROFILE----- */

    public function actionProfile() {
        $this->layout = 'main';
        $object = 'usr';

        $model = $this->findUser(Yii::$app->user->id);
        $formm = new \frontend\modules\cabinet\models\UserForm();
        $formm->user_firstname = $model->user_firstname;
        $formm->id = $model->id;
        $formm->user_pasport = $model->user_pasport;    /* edrpou */
        $formm->user_adress_1 = $model->user_adress_1; /* polnoe nazvanie */
        $formm->status = $model->status;
        $formm->gender = $model->gender;
        $formm->user_type = $model->user_type;

        if ($formm->load(Yii::$app->request->post())) {

            if (UploadedFile::getInstance($formm, 'user_doc') != NULL) {

                $file = UploadedFile::getInstance($formm, 'user_doc');

                $filename = $file->baseName;

                $new_filename = $filename . $formm->id . date("Ymdhis");

                $formm->user_doc = $new_filename . '.' . $file->extension;
            }

            if ($formm->save()) {

                if ($file != NULL) {
                    $file->saveAs('../../frontend/web/profile/files/' . $formm->user_doc);
                }

                $model = $this->findUser(Yii::$app->user->id);

                $chat = Dialog::find()->joinWith('user')->where('object = "' . $object . '" AND object_id=' . Yii::$app->user->id)->orderBy('d_date DESC')->all();

                return $this->render('usr_update_form', ['obj_id' => $id, 'formm' => $formm, 'model' => $model, 'chat' => $chat, 'object' => $object,
                ]);
            }
        } else {

            return $this->render('usr_update_form', ['obj_id' => $id, 'formm' => $formm, 'model' => $model, 'chat' => $chat, 'object' => $object,
            ]);
        }
    }

    /* ------------------ */
    
    
    public function actionCitylist($q = null, $id = null) {
    \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
    $out = ['results' => ['id' => '', 'text' => '']];
    if (!is_null($q)) {
        $query = new \yii\db\Query;
        $query->select(['g_id as id'," CONCAT(g_c_name,' (',g_o_name,' ',g_r_name,')')  AS text"])
            ->from('geography')
            ->where(" `g_c_name` like '".$q."%' ")
            ->limit(60);
        $command = $query->createCommand();
        $data = $command->queryAll();
        $out['results'] = array_values($data);
    }
    elseif ($id > 0) {
        $out['results'] = ['id' => $id, 'text' => \common\models\Geography::findOne($id)->g_c_name];
    }
    return $out;
}
    
}
