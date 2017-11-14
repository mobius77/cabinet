<?php

namespace frontend\modules\cabinet\controllers;

use Yii;
use yii\web\Controller;
use yii\helpers\Url;
use frontend\modules\cabinet\models\OrdStatus;
use frontend\modules\cabinet\models\UserGroups;
use yii\helpers\Json;

class PropController extends Controller {

    public function actionOrdStatus() {
        $this->layout = 'main';

        $status_all = OrdStatus::find()->where('')->orderBy('s_id')->all();

        Url::remember();
        return $this->render('ord_status', ['status_all' => $status_all]);
    }

    public function actionUpdateOrdStatus($s_id) {
        $this->layout = 'main';

        $model_os = OrdStatus::find()->where(['s_id' => $s_id])->one();

        $formm = new \frontend\modules\cabinet\models\OrdStatusForm();

        $formm->s_id = $model_os->s_id;
        $formm->s_name = $model_os->s_name;
        $formm->s_color = $model_os->s_color;


        if ($formm->load(Yii::$app->request->post())) {

            $formm->save();

            $model_os = OrdStatus::find()->where(['s_id' => $s_id])->one();

            return $this->render('update-ord-status', ['obj_id' => $s_id, 'formm' => $formm, 'model' => $model_os, 'way' => 'back']);
        } else {

            return $this->render('update-ord-status', ['obj_id' => $s_id, 'formm' => $formm, 'model' => $model_os, 'way' => 'canc']);
        }
    }

    public function actionAddOrdStatus() {
        $this->layout = 'main';

        $formm = new \frontend\modules\cabinet\models\OrdStatusForm();

        if ($formm->load(Yii::$app->request->post())) {
            if ($formm->add()) {

                return $this->redirect([Url::previous()]);
            }
        }

        return $this->render('update-ord-status', ['formm' => $formm]);
    }

    public function actionDeleteOrdStatus($s_id) {

        $model_os = OrdStatus::find()->where(['s_id' => $s_id])->one();

        if ($model_os->delete()) {

            return $this->redirect([Url::previous()]);
        }
    }

    public function actionUsrGroups() {
        $this->layout = 'main';

        $groups_all = UserGroups::find()->where('')->orderBy('ug_id')->all();

        Url::remember();
        return $this->render('usr_groups', ['groups_all' => $groups_all]);
    }

    public function actionUpdateUsrGroup($ug_id) {
        $this->layout = 'main';

        $model_os = UserGroups::find()->where(['ug_id' => $ug_id])->one();

        $formm = new \frontend\modules\cabinet\models\UsrGroupForm();

        $formm->ug_id = $model_os->ug_id;
        $formm->ug_name = $model_os->ug_name;
        $formm->ug_skidka = $model_os->ug_skidka;
        $formm->ug_price = $model_os->ug_price;

        if ($formm->load(Yii::$app->request->post())) {

            $formm->save();

            $model_os = UserGroups::find()->where(['ug_id' => $ug_id])->one();

            return $this->render('update-usr-group', ['obj_id' => $ug_id, 'formm' => $formm, 'model' => $model_os, 'way' => 'back']);
        } else {

            return $this->render('update-usr-group', ['obj_id' => $ug_id, 'formm' => $formm, 'model' => $model_os, 'way' => 'canc']);
        }
    }

    public function actionAddUsrGroup() {
        $this->layout = 'main';

        $formm = new \frontend\modules\cabinet\models\UsrGroupForm();

        if ($formm->load(Yii::$app->request->post())) {
            if ($formm->add()) {

                return $this->redirect([Url::previous()]);
            }
        }

        return $this->render('update-usr-group', ['formm' => $formm]);
    }

    public function actionDeleteUsrGroup($ug_id) {

        $model_os = UserGroups::find()->where(['ug_id' => $ug_id])->one();

        if ($model_os->delete()) {

            return $this->redirect([Url::previous()]);
        }
    }

    public function actionNoty() {
        $this->layout = 'main';
        return $this->render('noty');
    }
    
    
   /*----DISCOUNTS START-------------*/
    
      public function actionDiscount() {
          $this->layout = 'main';
          
        if (Yii::$app->request->post('hasEditable')){
           $field = Yii::$app->request->post('editableAttribute');
           $editableIndex = Yii::$app->request->post('editableIndex');
           $editableKey = Yii::$app->request->post('editableKey');
           
           $value = Yii::$app->request->post('ValTree4')[$editableIndex][$field];
           if ($value!=null) {
              $tree = \common\models\ValTree4::findOne($editableKey); 
           }
            else {
                $value = Yii::$app->request->post('ValTree6')[$editableIndex][$field];
                $tree = \common\models\ValTree6::findOne($editableKey); 
            }
           
          
           $disc = \common\models\Discount::find()->where('tree_id = '.$tree->tree_id)->one();
           if ($disc==null) 
                { 
                    $disc = new \common\models\Discount();
                    $disc->tree_id = $tree->tree_id;
                }
           $disc->{$field} = $value;
           $disc->save();
           return Json::encode(['output'=>'', 'message'=>'']);
        }  
        else
        {
            $type = Yii::$app->request->get('type');
            return $this->render('discount',['type'=>$type]);
        }
    }
    
    
    /*----DISCOUNTS END-------------*/
    
    

}
