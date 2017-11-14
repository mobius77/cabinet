<?php

namespace frontend\modules\cabinet\controllers;

use Yii;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use common\models\LoginForm;
use yii\widgets\ActiveForm;
use yii\web\NotFoundHttpException;
use yii\data\ArrayDataProvider;
use common\models\User;
use yii\helpers\Url;
use common\models\Params;
use common\models\Dialog;
use common\models\TreeClass;
use common\models\Tree;
use common\models\SignupForm;
use common\models\SysLang;
use yii\web\UploadedFile;
use frontend\modules\cabinet\models\UserContacts;
use kartik\grid\EditableColumnAction;
use yii\helpers\ArrayHelper;
use yii\helpers\Json;

/**
 * Site controller
 */
class UsrController extends Controller {

    public $tree;

    /**
     * @inheritdoc
     */
 /*   public function behaviors() {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                        [
                        'actions' => ['login', 'error', 'getaccess', 'logout', 'signup'],
                        'allow' => true,
                    ],
                        [
                        'actions' => ['params'],
                        'allow' => true,
                        'roles' => ['admin'],
                    ],
                        [
                        'actions' => ['users', 'update-user', 'delete-user', 'add-usr-contact', 'add-usr-adress',
                            'update-usr-adress', 'usr-contacts', 'usr-adress', 'update-usr-contacts', 'delete-usr-adress',
                            'delete-usr-contacts', 'adm-page', 'adm-contacts', 'cont-list-show'],
                        'allow' => true,
                        'roles' => ['admin', 'moder'],
                    ],
                        [
                        'actions' => ['logout', 'index', 'change-lang', 'add-dial', 'save-map', 'profile', 'add-project', 'update-project-map', 'update-project', 'projects', 'addnodeone', 'project-file-upload', 'deleteitem', 'turnnode', 'reordertree',
                            'usr-contacts', 'usr-adress', 'add-usr-contact', 'add-usr-adress',
                            'delete-usr-adress', 'delete-usr-contacts', 'update-user', 'update-usr-adress', 'update-usr-contacts'
                            ],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                
                ],
            ],
        ];
    }*/

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

    public function actionSignup() {
        $model = new SignupForm();
        if ($model->load(Yii::$app->request->post())) {
            if ($user = $model->signup()) {


                if (Yii::$app->getUser()->login($user)) {
                    return $this->goHome();
                }
            }
        }
        return $this->redirect(['/login']);
        /* return $this->render('login', [
          'model' => $model,
          ]); */
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

    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex() {

        $this->layout = 'main';

        return $this->render('index');
    }

    public function actionParams() {
        $this->layout = 'main';

        $params = Yii::$app->request->post('kvform');
        if ($params != null) {
            foreach ($params as $key => $param) {
                $m = Params::find()->where('p_name="' . $key . '"')->one();
                $m->p_value = $param;
                $m->save();
            }
        }

        return $this->render('params');
    }

    /* ------------------- */

    /* -------------------- */




    /* -------CHAT------- */

    public function actionAddDial() {

        if (Yii::$app->request->post('message') != '') {
            $model = new Dialog();
            $model->object = Yii::$app->request->post('obj');
            $model->content = Yii::$app->request->post('message');
            $model->user_id = Yii::$app->user->id;
            $model->object_id = Yii::$app->request->post('obj_id');
            $model->save();
        }
        $chat = Dialog::find()->joinWith('user')->where('object = "' . $model->object . '" AND object_id=' . $model->object_id)->orderBy('d_date DESC')->all();
        return $this->renderPartial('chat_items', ['chat' => $chat]);
    }

    /* ------------------ */

    /* -----PROJECTS------ */

    public function actionProjects() {


        /*  $users = \common\models\User::find()->where('')->orderBy('status, username')->all(); */

        $this->layout = 'main';
        $tree = new TreeClass("Tree", $id_tree);

        $param = array("action" => "/admin/adm/addeditnode",
            "mod" => "admt/adm/index",
            "id_tree" => $id_tree,
            "opti" => "update_node",
            'id_tree_parent' => $parent_id);


        return $this->render('projects', [
                    'tree' => $tree,
                    'param' => $param,
        ]);
    }

    public function actionAddProject() {
        $this->layout = 'main';

        $user_id = Yii::$app->user->id;
        $user = User::findOne($user_id);
        if ($user->status == 0)
            return $this->redirect(['projects']);

        Yii::$app->session->set('lang', 'uk');
        $model = new \common\models\ValTree33();
        $model->status = 0;


        if (Yii::$app->request->isAjax) {
            $model->load(Yii::$app->request->post());
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($model);
        } else {
            if ($model->load(Yii::$app->request->post())) {

                $node = Tree::findOne(2359);
                $NewNode = new Tree;
                $NewNode->tree_name = $model->nname;
                $NewNode->tree_pid = 2359;
                $NewNode->tm_id = 2361;
                $NewNode->is_enable = 1;
                $NewNode->prependTo($node);
                /*  $NewNode->tree_url = $NewNode->tree_id; */
                $NewNode->tree_url = $this->getUrl($model->nname);
                $NewNode->saveNode();



                Yii::$app->session->set('lang', 'uk');

                $model->lang = 'uk';
                $model->tree_id = $NewNode->tree_id;
                $model->user_id = Yii::$app->user->id;

                if ($model->save()) {
                    echo "22222222222222";


                    /*   $tr = Tree::findOne($model->tree_id);
                      $tr->tree_url = getUrl($model->nname); */

                    /* создаем пустые строки для всех остальных языков */
                    $langs = \common\models\SysLang::find()->where('lang_kod<>"uk"')->all();
                    if ($langs != null) {
                        foreach ($langs as $lang) {
                            $it = \common\models\ValTree33::find()->where('tree_id=' . $model->tree_id . ' AND lang="' . $lang->lang_kod . '"')->one();
                            if ($it == null) {
                                $iteml = new \common\models\ValTree33();
                                $iteml->status = $model->status;
                                $iteml->lang = $lang->lang_kod;
                                $iteml->tree_id = $model->tree_id;
                                $iteml->user_id = Yii::$app->user->id;
                                $iteml->p_categ = $model->p_categ;
                                $iteml->save();
                                /*  echo "<pre>"; */
                                /*  print_r($iteml->getErrors()); */
                                /*   print_r($iteml); */
                            }
                            unset($it);
                        }
                    }


                    /* --------NN--------- */


                    $mes = Yii::t('main', 'Проект') . ' ' . $model->nname . ' ' . Yii::t('main', 'зареєстровано на Інвестиційному порталі Запорізької області') . '!';
                    $mes_extra = Yii::t('main', 'Ваш проект буде розглянуто адміністратором сайту. Про результати розгляду Вас буде повідомлено за даною електронною поштою.');
                    /*
                      $adr_name = \common\models\Signup::
                     */

                    if (Yii::$app->user->id != 2) {
                        $usr = \common\models\User::findOne(Yii::$app->user->id);

                        $user = $usr->user_firstname;

                        \Yii::$app->mailer->compose('notify', ['user' => $user, 'message' => $mes, 'message_extra' => $mes_extra])
                                ->setFrom(['pm@investment.zoda.gov.ua' => 'Управління зовнішньоекономічної діяльності ЗОДА'])
                                ->setTo(Yii::$app->user->identity->username)
                                ->setSubject('Реєстрація проекту на Інвестиційному порталі Запорізької області')
                                ->send();

                        $user = 'ID: ' . $model->user_id;
                        $mes = Yii::t('main', 'Назва проекту') . ': ' . $model->nname . ' ' . Yii::t('main', 'Дата та час реєстрації') . ': ' . date("Y-m-d H:i:s");
                        $mes_extra = Yii::t('main', 'Зареєстрував новий проект');
                        \Yii::$app->mailer->compose('notify_admin', ['user' => $user, 'message' => $mes, 'message_extra' => $mes_extra])
                                ->setFrom(['pm@investment.zoda.gov.ua' => 'Управління зовнішньоекономічної діяльності ЗОДА'])
                                ->setTo(explode(',', Params::find()->where('name=:name', array(':name' => 'subscribe'))->one()->value))
                                ->setSubject('Реєстрація проекту на Інвестиційному порталі Запорізької області')
                                ->send();

                        unset($usr);
                    }
                }

                return $this->redirect(['projects']);
            } else {
                return $this->render('projects_new', [
                            'model' => $model,
                            'id' => $user_id,
                ]);
            }
        }
    }

    public function actionUpdateProject($id) {
        $this->layout = 'main';
        if (Yii::$app->request->post('ValTree46') != '') {
            $val = Yii::$app->request->post('ValTree46');
            $index = Yii::$app->request->post('editableIndex');
            $atr = Yii::$app->request->post('editableAttribute');
            $item_id = Yii::$app->request->post('editableKey');

            $item = \common\models\ValTree46::findOne($item_id);
            $item->{$atr} = $val[$index][$atr];
            $item->save();
            return '{
                    "status": "success",
                    "data": {
                      
                    },
                    "message": null
                  }';
        }




        $object = 'prj';
        $model = $this->findProject($id);

        $chat = Dialog::find()->joinWith('user')->where('object = "' . $object . '" AND object_id=' . $id)->orderBy('d_date DESC')->all();


        $old_status = $model->status;

        if ($model->load(Yii::$app->request->post()) && $model->save()) {



            /*  $model = $this->findProject($id);
              Yii::$app->db->createCommand(' UPDATE val_tree_33 SET status='.$model->status.' WHERE tree_id='.$model->tree_id)->execute(); */

            $tr = Tree::findOne($model->tree_id);
            $tr->tree_url = $this->getUrl(Yii::$app->request->post('tree_url'), $model->tree_id);
            $tr->saveNode();

            /* --------NN--------- */

            if (($old_status != 2) && ($model->status == 2)) {


                $us = \common\models\User::findOne($model->user_id);

                $mes = Yii::t('main', 'Проект') . ' ' . $model->nname . ' ' . Yii::t('main', 'успішно активовано на Інвестиційному порталі Запорізької області') . '!';

                $user = $us->user_firstname;

                \Yii::$app->mailer->compose('notify', ['user' => $user, 'message' => $mes, 'message_extra' => $mes_extra])
                        ->setFrom(['pm@investment.zoda.gov.ua' => 'Управління зовнішньоекономічної діяльності ЗОДА'])
                        ->setTo($us->username)
                        ->setSubject('Активація проекту на Інвестиційному порталі Запорізької області')
                        ->send();

                /*   $user = 'ID: '.$model->user_id;
                  $mes = Yii::t('main', 'Назва проекту').': '.$model->nname.' '.Yii::t('main', 'Дата та час внесення змін').': '.date("Y-m-d H:i:s");
                  $mes_extra = Yii::t('main', 'Вніс зміни до проекту');
                  \Yii::$app->mailer->compose('notify_admin', ['user' => $user,'message'=>$mes, 'message_extra' => $mes_extra])
                  ->setFrom([\Yii::$app->params['supportEmail'] => \Yii::$app->name .' '. Yii::t('main', 'робот')])
                  ->setTo(explode(',',Params::find()->where('name=:name', array(':name' => 'email'))->one()->value))
                  ->setSubject('Notify ' . \Yii::$app->name)
                  ->send();
                 */
            }



            $chat = Dialog::find()->joinWith('user')->where('object = "' . $object . '" AND object_id=' . $id)->orderBy('d_date DESC')->all();
            return $this->render('projects_update', ['obj_id' => $id, 'model' => $model, 'chat' => $chat, 'object' => $object]);
        } else {

            return $this->render('projects_update', ['obj_id' => $id, 'model' => $model, 'chat' => $chat, 'object' => $object]);
        }
    }

    public function actionUpdateProjectMap($id) {
        $this->layout = 'main';
        /*  $this->layout = 'map'; */

        return $this->render('projects_update_map');
    }

    public function actionSaveMap() {

        $id = Yii::$app->request->post('id');
        $item = \common\models\ValTree33::find()->where('tree_id=' . $id . ' AND lang="uk"')->one();

        if ((Yii::$app->user->identity->role > 1) || (Yii::$app->user->id == $item->user_id)) {
            $val['col'] = Yii::$app->request->post('collection');
            $val['zoom'] = Yii::$app->request->post('zo');
            $val['centr'] = Yii::$app->request->post('ce');

            $item->p_map = serialize($val);

            $item->save();
        }
    }

    function getUrl($title, $tree_id = null) {
        $r = array('а', 'б', 'в', 'г', 'д', 'е', 'ё', 'ж', 'з', 'и', 'і', 'ї', 'й', 'к', 'л', 'м', 'н', 'о', 'п', 'р', 'с', 'т', 'у', 'ф', 'х', 'ц', 'ч', 'ш', 'щ', 'ъ', 'ы', 'ь', 'э', 'ю', 'я', ' ');
        $l = array('a', 'b', 'v', 'g', 'd', 'e', 'e', 'g', 'z', 'i', 'i', 'i', 'y', 'k', 'l', 'm', 'n', 'o', 'p', 'r', 's', 't', 'u', 'f', 'h', 'c', 'ch', 'sh', 'sh', '', 'y', 'y', 'e', 'yu', 'ya', '-');
        $small = mb_convert_case($title, MB_CASE_LOWER, "UTF-8");
        $s = str_replace($r, $l, $small);
        $s = preg_replace("/[^\w\-]/", "$1", $s);
        $s = preg_replace("/\-{2,}/", '-', $s);
        $title_t = trim($s, '-');

        $fl = '';
        if ($tree_id != null)
            $fl = ' AND tree_id<>' . $tree_id;

        $url = Tree::find()->where('tree_url="' . $title_t . '"' . $fl)->one();

        $i = 1;
        while ($url != null) {
            $title_t = $title . $i;
            $i++;
            $url = Tree::find()->where('tree_url="' . $title_t . '"' . $fl)->one();
        }
        return $title_t;
    }

    protected function findProject($id) {

        (Yii::$app->session->get('lang') !== null) ? $lang_def = Yii::$app->session->get('lang') : $lang_def = SysLang::find()->where('lang_def=1')->one()->lang_kod;

        if (($model = \common\models\ValTree33::find()->where('tree_id=' . $id . ' AND lang="' . $lang_def . '"')->one()) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    public function actionProjectFileUpload($id) {


        if (Yii::$app->request->isPost) {



            //Try to get file info
            $upload_image_0 = \yii\web\UploadedFile::getInstanceByName('upload_image-0');
            $upload_image_1 = \yii\web\UploadedFile::getInstanceByName('upload_image-1');


            //If received, then I get the file name and asign it to $model->image in order to store it in db

            $isfile = false;
            if (!empty($upload_image_0)) {
                $isfile = true;
                $image_name = $upload_image_0->name;
                $tm_id = 2473;
                $modelname = '\common\models\ValTree46';
                $fname = 'ffile';
                $path = "files";
            }


            if (!empty($upload_image_1)) {
                $isfile = true;
                $image_name = $upload_image_1->name;
                $tm_id = 2474;
                $modelname = '\common\models\ValTree47';
                $fname = 'ffoto';
                $path = "photos";
            }

            if ($isfile) {

                //  $model->image = $image_name;
                $today = getdate();
                $str = $today['hours'] . $today['minutes'] . $today['seconds'];
                $filename = $str . "_" . $image_name;


                $model = new $modelname();

                $node = Tree::findOne($id);
                $NewNode = new Tree;
                $NewNode->tree_name = '';
                $NewNode->tree_pid = $id;
                $NewNode->tm_id = $tm_id;
                $NewNode->is_enable = 1;
                $NewNode->appendTo($node);
                $NewNode->tree_url = $NewNode->tree_id;
                $NewNode->saveNode();

                $model->{$fname} = $filename;
                $model->lang = 'uk';
                $model->tree_id = $NewNode->tree_id;


                if ($model->save()) {

                    if (!empty($upload_image_0)) {
                        $upload_image_0->saveAs('../../frontend/web/userfiles/projects/' . $path . '/' . $filename);
                    }
                    if (!empty($upload_image_1)) {


                        $uploaddir = "../../frontend/web/userfiles/projects/" . $path . "/";


                        $upload_image_1->saveAs($uploaddir . 'orig/' . $filename);


                        $tmm = \common\models\SysTemplateField::findOne(181);


                        if ($tmm->tf_pr1 != '') {
                            $arr_img = explode('/', $tmm->tf_pr1);
                        } else {
                            unset($arr_img);
                        }



                        $i = 0;
                        $exten = end(explode(".", $filename));

                        if ($arr_img != null) {
                            foreach ($arr_img as $param) {
                                $dim = explode('-', $param);
                                if (!file_exists($uploaddir . $i))
                                    mkdir($uploaddir . $i);
                                chmod($uploaddir . $i, 0777);
                                $udir = $uploaddir . $i . '/';
                                $image = Yii::$app->image->load($uploaddir . "orig/" . $filename);

                                if ($exten == 'png') {
                                    $image->resize($dim[0], $dim[1], \yii\image\drivers\Image::CROP)->save($udir . $filename);
                                } else {
                                    $image->resize($dim[0], $dim[1], \yii\image\drivers\Image::CROP)->background('#fff')->save($udir . $filename);
                                }
                                /*  $this->chimage($udir, $uploaddir."orig/" ,$filename, $dim[0], $dim[1], 100); */
                                $i++;
                            }
                        }
                        $dim = explode('-', $tmm->tf_pr2);
                        $udir = $uploaddir . "tumb" . '/';
                        /*  $file=Yii::getAlias('@app/pass/to/file'); */
                        $image = Yii::$app->image->load($uploaddir . "orig/" . $filename);
                        if ($exten == 'png') {
                            $image->resize($dim[0], $dim[1], \yii\image\drivers\Image::CROP)->save($udir . $filename);
                        } else {
                            $image->resize($dim[0], $dim[1], \yii\image\drivers\Image::CROP)->background('#fff')->save($udir . $filename);
                        }
                    }

                    /* создаем пустые строки для всех остальных языков */
                    $langs = \common\models\SysLang::find()->where('lang_kod<>"uk"')->all();
                    if ($langs != null) {
                        foreach ($langs as $lang) {
                            $it = $modelname::find()->where('tree_id=' . $model->tree_id . ' AND lang="' . $lang->lang_kod . '"')->one();
                            if ($it == null) {
                                $iteml = new $modelname();
                                $iteml->lang = $lang->lang_kod;
                                $iteml->{$fname} = $filename;
                                $iteml->tree_id = $model->tree_id;
                                $iteml->save();
                            }
                            unset($it);
                        }
                    }
                }
            }
        }

        return '{}';
    }

    public function actionDeleteitem() {
        $tree_id = Yii::$app->request->get('tree_id');

        $tr = Tree::findOne($tree_id);
        $item = \common\models\ValTree33::find()->where('tree_id=' . $tr->tree_pid . ' AND lang="uk"')->one();

        if ($item != null) {
            if (($item->user_id == Yii::$app->user->id) || ( Yii::$app->user->identity->role > 1)) {
                $this->tree = new TreeClass("Tree", $tree_id);
                $tree_id = $this->tree->Remove($tree_id);
            }
        }
    }

    public function actionTurnnode() {
        $id_tree = Yii::$app->request->post('id');

        $tr = Tree::findOne($id_tree);
        $item = \common\models\ValTree33::find()->where('tree_id=' . $tr->tree_pid . ' AND lang="uk"')->one();

        if ($item != null) {
            if (($item->user_id == Yii::$app->user->id) || ( Yii::$app->user->identity->role > 1)) {
                $this->tree = new TreeClass("Tree", $id_tree);
                return $this->tree->TurnNode($id_tree);
            }
        }
    }

    public function actionReordertree() {

        $tree = new TreeClass();

        $r = Yii::$app->request->post('r');
        $tree_id = Yii::$app->request->get('tree_id');

        $item = \common\models\ValTree33::find()->where('tree_id=' . $tree_id . ' AND lang="uk"')->one();

        if ($item != null) {
            if (($item->user_id == Yii::$app->user->id) || ( Yii::$app->user->identity->role > 1)) {
                $tree->reorder($tree_id, $r);
            }
        }
    }

    /* --------------------- */

    public function actionChangeLang() {
        Yii::$app->session->set('lang', Yii::$app->request->get('kod'));
        return true;
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
                
                return $this->render('update-user', ['upd_form' =>$upd_form, 'contacts' => $contacts, 'adress' => $adress, 'usr' => $model]);
            }
        } else {
            
            $upd_form = $this->renderPartial('usr_update_form', ['obj_id' => $id, 'formm' => $formm, 'model' => $model, 'chat' => $chat, 'object' => $object,
                     'b_crumb' => 'adm']);
            
            Url::remember();
            
            return $this->render('update-user', ['upd_form' =>$upd_form, 'contacts' => $contacts, 'adress' => $adress, 'usr' => $model]);
        }
    }

    public function actionUpdateAdress() {
        /*  $this->layout= 'main'; */
        $form_a = new \frontend\modules\cabinet\models\AdressForm();
        /*
          return $this->render('update-adress');
         */
        if ($form_a->load(Yii::$app->request->post())) {



            if ($form_a->save()) {

                return $this->redirect(['usr/users']);
            } else {
                echo 'Ничего не пришло';
            }
        }
    }

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
            $cur_usr = $this->findUser(Yii::$app->user->id);
            if ($cur_usr->role == 10) {                 
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
            $formm->c_email = $model_c->c_email;
            $formm->c_phone = $model_c->c_phone;
            $formm->c_post = $model_c->c_post;
            $formm->c_note = $model_c->c_note;
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
        
        if (Yii::$app->request->get('u_id') && Yii::$app->request->get('u_id') != '' ) {
            $u_id = Yii::$app->request->get('u_id');
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
            if ($user->role != 10) {
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

        $contact = \frontend\modules\cabinet\models\UserContacts::find()->
                where(['c_id' => $c_id])->
                one();
        
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

        $c_id = $model_a->c_id;

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

    public function actionAdmContacts() {
        /*
          $searchModel = new \frontend\modules\cabinet\models\search\ContactSearch;
          $searchModel->u_id = Yii::$app->user->id;
          $dataProvider = $searchModel->search(Yii::$app->request->getQueryParams());

          $html = $this->renderPartial('usr_contacts', [
          'dataProvider' => $dataProvider,
          'searchModel' => $searchModel,
          'u_id' => Yii::$app->user->id
          ]);
         */

        $kkkk = '<h1>FFFFFUUUUUUUUUUUU</h1>';

        return Json::encode($kkkk);
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



    /**
     * Login action.
     *
     * @return string
     */
    public function actionLogin() {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        return $this->redirect(['../login']);

        $this->layout = 'login';

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        } else {
            /*   return $this->redirect(['../login']); */
            return $this->render('login', [
                        'model' => $model,
            ]);
        }
    }

    /**
     * Logout action.
     *
     * @return string
     */
    public function actionLogout() {
        Yii::$app->user->logout();
        return $this->redirect('/login');
        /*  return $this->goHome(); */
    }

}
