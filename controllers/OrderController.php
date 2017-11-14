<?php

namespace frontend\modules\cabinet\controllers;

use Yii;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use common\models\Tree;
use common\models\TreeClass;
use \common\models\MOrders;
use \common\models\MOrderItems;
use \common\models\MOrderReq;

/*testfftest*/

/**
 * Site controller
 */
class OrderController extends Controller {

    public $tree;

    /**
     * @inheritdoc
     */
    public function behaviors() {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['login', 'error', 'getaccess','logout','signup'],
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
                        'actions' => ['render-adr','adrlist','setnests','edit-order','turnnode', 'logout', 'index', 'change-lang' ,'add-dial', 'save-map' ,'profile', 'add-project', 'update-project-map' ,'update-project', 'projects', 'addnodeone', 'project-file-upload', 'deleteitem', 'turnnode', 'reordertree'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                   /* 'logout' => ['post'],*/
                ],
            ],
        ];
    }

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

    public function actionRenderAdr()  {
        $c_id = Yii::$app->request->get('c_id');
        $a_id = Yii::$app->request->get('a_id');
        return $this->renderPartial('ajax_adr',['c_id'=>$c_id, 'a_id'=>$a_id]);
    }
    
    
    public function actionAdrlist()
    {
        /* список адресов для выпадающего списка */
    $ids = $_POST['depdrop_parents'];

        $id = $ids[0];

        if ($id != '') {
            $list = \frontend\modules\cabinet\models\UserAdress::find()->
                            where('c_id=' . $id)->all();
        } else {
            $id = NULL;
        }
        $selected = null;
        if ($id != null && count($list) > 0) {
            $selected = '';
            foreach ($list as $i => $account) {
                 $out[] = ['id' => $account['a_id'], 'name' => $account->aCity->g_c_name .', № Отделения: '.$account['a_adr'].' ('.$account['a_note'].')'];
                if ($i == 0) {
                    $selected = $account['a_id'];
                }
            }
            // Shows how you can preselect a value
            echo \yii\helpers\Json::encode(['output' => $out, 'selected' => $selected]);
            return;
        }
    }
    
     public function actionTurnnode()
    {
        if ($item = MOrderReq::findOne(Yii::$app->request->post('id')))
        {
            $order=  MOrders::findOne($item->order_id);
            switch ($item->item_status)
            {
                case '3':
                    $item->item_status = 5;
                    try { 
                                \Yii::$app->mailer->compose('returnedOrder', ['order'=>$order ])
                                         ->setFrom(['info@laglore.com'=>'Laglore.com'])
                                         ->setTo([$order->order_email, \common\models\Params::find()->where('name=:name', array(':name'=>'email'))->one()->value])
                                         ->setSubject('Laglore.com - Order Return information (Order No. '.$order->order_uuid.')')
                                         ->send();
                                   }
                             catch(\Exception $e){}
                    break;
                case '4':
                    $item->item_status = 6;
                     try { 
                                \Yii::$app->mailer->compose('exchangedOrder', ['order'=>$order ])
                                         ->setFrom(['info@laglore.com'=>'Laglore.com'])
                                         ->setTo([$order->order_email, \common\models\Params::find()->where('name=:name', array(':name'=>'email'))->one()->value])
                                         ->setSubject('Laglore.com - Order Exchange information (Order No. '.$order->order_uuid.')')
                                         ->send();
                                   }
                             catch(\Exception $e){}
                    break;
                case '5':
                    $item->item_status = 3;
                    break;
                case '6':
                    $item->item_status = 4;
                    break;
            }
            $item->save();
        }
    }
    
    public function actionEditOrder()
    {
          
         $id_tree = 164;
            $this->layout='main';
            
            $this->tree = new TreeClass("Tree",$id_tree);

            $order_id = Yii::$app->request->get('order_id');
            
            $model=  MOrders::findOne($order_id);
         $items = Yii::$app->request->post('MOrderItems');

            if (($items!='null')&&($items!=''))
            {
                    $idi = Yii::$app->request->post('editableKey');
                    $index = Yii::$app->request->post('editableIndex');
                    $ritem = MOrderItems::findOne($idi);
                    $order=  MOrders::findOne($ritem->order_id);
                   
                    if (isset($items[$index]['item_ret']))
                    {
                        $ritem->item_ret = $items[$index]['item_ret'];
                        $ritem->save();
                        return true;
                    }

                    if (isset($items[$index]['item_status']))
                    {
                        $old_status = $ritem->item_status;
                        $new_status = $items[$index]['item_status'];
                        
                        $ritem->item_status = $items[$index]['item_status'];
                        $ritem->save();
                        
                        return true;
                    }                    
                    
                 /*   if (isset($items[$index]['order_decnum']))
                    {
                        $ritem->order_decnum = $items[$index]['order_decnum'];
                        $ritem->save();
                        return true;
                    }
                    else if (isset($items[$index]['order_status']))
                    {
                    
               
                    $old_status = $ritem->order_status;
                    $new_status = $items[$index]['order_status'];
                    $ritem->order_status = $items[$index]['order_status'];
                    if($ritem->save()) {
                       
                        $dec = [0,10];
                        $inc = [1,2,3];
                        
                        
                        $cart_items = MOrderItems::find()->joinWith('tree')->where('order_id='.$idi)->all();
                        $db = Yii::$app->db;
                        
                        if ((in_array($old_status, $dec))&&(in_array($new_status, $inc)))
                        {
                            foreach($cart_items as $cart_item)
                            {
                                $it = Tree::findOne($cart_item->tree_id);
                                switch ($it->tm_id) 
                                {
                                   case 14:
                                       $db->createCommand("UPDATE val_tree_21 SET `kolvo` = `kolvo`- ".$cart_item->item_count." WHERE pid=".$cart_item->tree_id." AND size like '%\"".$cart_item->item_size."\"%'")->execute();
                                       break;
                                   case 60:
                                       $db->createCommand("UPDATE val_tree_19 SET `kolvo` = `kolvo`- ".$cart_item->item_count." WHERE tree_id=" . $cart_item->tree_id)->execute();
                                       break;
                               }
                            }
                        }

                        if ((in_array($old_status, $inc ))&&(in_array($new_status, $dec)))
                        {
                            foreach($cart_items as $cart_item)
                            {
                                $it = Tree::findOne($cart_item->tree_id);
                                switch ($it->tm_id) 
                                {
                                   case 14:
                                       $db->createCommand("UPDATE val_tree_21 SET `kolvo` = `kolvo`+ ".$cart_item->item_count." WHERE pid=".$cart_item->tree_id." AND size like '%\"".$cart_item->item_size."\"%'")->execute();
                                       break;
                                   case 60:
                                       $db->createCommand("UPDATE val_tree_19 SET `kolvo` = `kolvo`+ ".$cart_item->item_count." WHERE tree_id=" . $cart_item->tree_id)->execute();
                                       break;
                               }
                            }
                        }
                        
                        if ($new_status==3)
                        {
                                try { 
                                   \Yii::$app->mailer->compose('shippedOrder', ['order'=>$ritem , 'items'=>$cart_items])
                                            ->setFrom(['info@laglore.com'=>'Laglore.com'])
                                            ->setTo([$ritem->order_email, \common\models\Params::find()->where('name=:name', array(':name'=>'email'))->one()->value])
                                            ->setSubject('Laglore.com - Shipping Confirmation (Order No. '.$ritem->order_id.')')
                                            ->send();
                                    Yii::$app->session->setFlash('order','ture'); 
                                      }
                                catch(\Exception $e){}
                        }
                        
                        return true;
                    }
                    else {
                        return '{"output":""}';
                    }
                }*/
            }
            else
            {
                $items = Yii::$app->request->post('MOrders');
                $old_stat = $model->order_status;
                $new_status = $items['order_status'];
                 $model->load(Yii::$app->request->post());
                 $model->save();
                 
                 $cart_items = MOrderItems::find()->joinWith('tree')->where('order_id='.$model->order_id)->all();
                 
                 if (($new_status==3)&&($old_stat!=$new_status))
                        {
                                try { 
                                   \Yii::$app->mailer->compose('shippedOrder', ['order'=>$model , 'items'=>$cart_items])
                                            ->setFrom(['info@laglore.com'=>'Laglore.com'])
                                            ->setTo([$model->order_email, \common\models\Params::find()->where('name=:name', array(':name'=>'email'))->one()->value])
                                            ->setSubject('Laglore.com - Shipping Confirmation (Order No. '.$model->order_uuid.')')
                                            ->send();
                                    Yii::$app->session->setFlash('order','ture'); 
                                      }
                                catch(\Exception $e){}
                        }
                
                 if (($new_status==6)&&($old_stat!=$new_status))
                        {
                                try { 
                                   \Yii::$app->mailer->compose('shippedExchangeOrder', ['order'=>$model , 'items'=>$cart_items])
                                            ->setFrom(['info@laglore.com'=>'Laglore.com'])
                                            ->setTo([$model->order_email, \common\models\Params::find()->where('name=:name', array(':name'=>'email'))->one()->value])
                                            ->setSubject('Laglore.com - Exchange Shipping Confirmation (Order No. '.$model->order_uuid.')')
                                            ->send();
                                    Yii::$app->session->setFlash('order','ture'); 
                                      }
                                catch(\Exception $e){}
                        }                 
                 
            }
            
        
        
           
            echo $this->render('order_details',array('order'=>$model, 'order_id' => $order_id));
    }
    
    

    public function actionIndex()
	{
           
            $items = Yii::$app->request->post('MOrders');
            if ($items!='null')
            {
                    $idi = Yii::$app->request->post('editableKey');
                    $index = Yii::$app->request->post('editableIndex');
                    $ritem = MOrders::findOne($idi);
                    
                    if (isset($items[$index]['order_decnum']))
                    {
                        $ritem->order_decnum = $items[$index]['order_decnum'];
                        $ritem->save();
                        
                        
                        if (($ritem->order_decnum!='')&&($ritem->order_status==3)) {
                             try { 
                                 $cart_items = MOrderItems::find()->joinWith('tree')->where('order_id='.$idi)->all();
                                   \Yii::$app->mailer->compose('shippedOrder', ['order'=>$ritem , 'items'=>$cart_items])
                                            ->setFrom(['info@laglore.com'=>'Laglore.com'])
                                            ->setTo([$ritem->order_email, \common\models\Params::find()->where('name=:name', array(':name'=>'email'))->one()->value])
                                            ->setSubject('Laglore.com - Shipping Confirmation (Order No. '.$ritem->order_uuid.')')
                                            ->send();
                                    Yii::$app->session->setFlash('order','ture'); 
                                      }
                                catch(\Exception $e){}
                        }
                        
                        
                        
                        
                        return true;
                    }
                    else if (isset($items[$index]['order_date_ship'])) {
                        
                        $ritem->order_date_ship = $items[$index]['order_date_ship'];
                        $ritem->save();
                        
                        
                        if (($ritem->order_date_ship!='')&&($ritem->order_status==8)) {
                             try { 
                                 $cart_items = MOrderItems::find()->joinWith('tree')->where('order_id='.$idi)->all();
                                   \Yii::$app->mailer->compose('shippedOrderShip', ['order'=>$ritem , 'date_ship'=>$ritem->order_date_ship ])
                                            ->setFrom(['info@laglore.com'=>'Laglore.com'])
                                            ->setTo([$ritem->order_email, \common\models\Params::find()->where('name=:name', array(':name'=>'email'))->one()->value])
                                            ->setSubject('Regarding LAGLORE Pre-Order (Order #'.$ritem->order_uuid.')')
                                            ->send();
                                   
                                      }
                                catch(\Exception $e){}
                        }
                        
                        
                        
                        
                        return true;
                        
                        
                    }
                    else if (isset($items[$index]['order_status']))
                    {
                    
               
                    $old_status = $ritem->order_status;
                    $new_status = $items[$index]['order_status'];
                    $ritem->order_status = $items[$index]['order_status'];
                    if($ritem->save()) {
                        /* пересчитываем остаток */
                        $dec = [0,10];
                        $inc = [1,2,3];
                        
                        
                        $cart_items = MOrderItems::find()->joinWith('tree')->where('order_id='.$idi)->all();
                        $db = Yii::$app->db;
                        
                        if ((in_array($old_status, $dec))&&(in_array($new_status, $inc)))
                        {
                            foreach($cart_items as $cart_item)
                            {
                                $it = Tree::findOne($cart_item->tree_id);
                                switch ($it->tm_id) 
                                {
                                   case 14:
                                       $db->createCommand("UPDATE val_tree_21 SET `kolvo` = `kolvo`- ".$cart_item->item_count." WHERE pid=".$cart_item->tree_id." AND size like '%\"".$cart_item->item_size."\"%'")->execute();
                                       break;
                                   case 60:
                                       $db->createCommand("UPDATE val_tree_19 SET `kolvo` = `kolvo`- ".$cart_item->item_count." WHERE tree_id=" . $cart_item->tree_id)->execute();
                                       break;
                               }
                            }
                        }

                        if ((in_array($old_status, $inc ))&&(in_array($new_status, $dec)))
                        {
                            foreach($cart_items as $cart_item)
                            {
                                $it = Tree::findOne($cart_item->tree_id);
                                switch ($it->tm_id) 
                                {
                                   case 14:
                                       $db->createCommand("UPDATE val_tree_21 SET `kolvo` = `kolvo`+ ".$cart_item->item_count." WHERE pid=".$cart_item->tree_id." AND size like '%\"".$cart_item->item_size."\"%'")->execute();
                                       break;
                                   case 60:
                                       $db->createCommand("UPDATE val_tree_19 SET `kolvo` = `kolvo`+ ".$cart_item->item_count." WHERE tree_id=" . $cart_item->tree_id)->execute();
                                       break;
                               }
                            }
                        }
                        
                        if (($ritem->order_decnum!='')&&($new_status==3))
                        {
                                try { 
                                   \Yii::$app->mailer->compose('shippedOrder', ['order'=>$ritem , 'items'=>$cart_items])
                                            ->setFrom(['info@laglore.com'=>'Laglore.com'])
                                            ->setTo([$ritem->order_email, \common\models\Params::find()->where('name=:name', array(':name'=>'email'))->one()->value])
                                            ->setSubject('Laglore.com - Shipping Confirmation (Order No. '.$ritem->order_uuid.')')
                                            ->send();
                                    Yii::$app->session->setFlash('order','ture'); 
                                      }
                                catch(\Exception $e){}
                        }
                        
                        if (($ritem->order_decnum!='')&&($new_status==6))
                        {
                                try { 
                                   \Yii::$app->mailer->compose('shippedExchangeOrder', ['order'=>$ritem , 'items'=>$cart_items])
                                            ->setFrom(['info@laglore.com'=>'Laglore.com'])
                                            ->setTo([$ritem->order_email, \common\models\Params::find()->where('name=:name', array(':name'=>'email'))->one()->value])
                                            ->setSubject('Laglore.com - Exchange Shipping Confirmation (Order No. '.$ritem->order_uuid.')')
                                            ->send();
                                    Yii::$app->session->setFlash('order','ture'); 
                                      }
                                catch(\Exception $e){}
                        }
                        
                        return true;
                    }
                    else {
                        return '{"output":""}';
                    }
                }
            }
            
            
            
            
            /* Разрешаем доступ к загрузке файов через KCFINDER */
            Yii::$app->session->set('KCFINDER',['disabled' => false,]);
           
	    $this->layout='main';
		
            foreach($_POST as $key => $value){ $$key=$value;}
            foreach($_GET as $key => $value){ if (!isset($$key)) $$key=$value;}		
		
            if((!isset($id_tree))||($id_tree=="")) $id_tree=Tree::find()->where('lft = 2')->one()->tree_id;
 
            
            $id_tree = 164;
            
            $_SESSION["id_tree"] = serialize($id_tree);

            $this->tree = new TreeClass("tree",$id_tree);

            $model='';
            echo $this->render('index',array('model'=>$model, 'id_tree' => $id_tree));
	}
    
    
    
}
