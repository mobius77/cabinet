<?php

namespace frontend\modules\cabinet\controllers;

use Yii;
use yii\web\Controller;

use frontend\modules\cabinet\models\ConfForm;

/**
 * Site controller
 */
class DefaultController extends Controller {

    public $tree;

    /**
     * @inheritdoc
     */
  /*  public function behaviors() {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['login', 'error', 'getaccess','logout','signup','sendmsg', 'sendmsgn', 'submsg', 'recmsg'],
                        'allow' => true,
                    ],
                    [
                        'actions' => ['params'],
                        'allow' => true,
                        'roles' => ['admin'],
                    ],
                    [
                        'actions' => ['users', 'update-user', 'delete-user'],
                        'allow' => true,
                        'roles' => ['admin', 'moder'],
                    ],
                    [
                        'actions' => ['logout', 'index', 'change-lang' ,'add-dial', 'save-map' ,'profile', 'add-project', 'update-project-map' ,'update-project', 'projects', 'addnodeone', 'project-file-upload', 'deleteitem', 'turnnode', 'reordertree'],
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
        
        $this->layout= 'main';
        
        $formm = new ConfForm;    
        
        return $this->render('index', ['formm' => $formm]);
    }


}
