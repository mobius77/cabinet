<?php

use common\models\SysTemplate;
use common\models\Tree;
use yii\bootstrap\Tabs;



($param['id_tree'] == 0) ? $id_node_tree = $param['id_tree_parent'] : $id_node_tree = $param['id_tree'];


$item = $tree->Model->findOne($id_node_tree);

if ($item->tree_readonly == 0) {
    echo "<form  id=\"myform" . $param['id_tree'] . "\" name=\"myform" . $param['id_tree'] . "\" action=\"" . $param["action"] . "\" method=\"post\"  onsubmit=\"return true\" enctype=\"multipart/form-data\">\n";

    echo "<input type=\"hidden\" NAME=\"_csrf\" value=\"" . Yii::$app->request->getCsrfToken() . "\"> \n";
    echo "<input type=\"hidden\" NAME=\"paramtmid\" value=\"" . $param['tm_id'] . "\"> \n";
}


if (!isset($tm_id)) {
    $tm_id = $param['tf_id'];
}

if ($tm_id == 0) {
    if (isset($param['tm_id'])) {
        $tmnode = Tree::findOne($param['tm_id']);
        if ($tmnode->tm_id == 0) {
            $result = $tree->db->createCommand("SELECT tm_id FROM sys_template WHERE node_id = " . $param['tm_id'] . " and tm_ismain = 1");
        } else {
            $result = $tree->db->createCommand("SELECT tm_id FROM sys_template WHERE node_id = " . $tmnode->tm_id . " and tm_ismain = 1");
        }
    } else {

        /*
          Если шаблон наследник, то форму будем получать от родителя.
         */
        $tmnode = Tree::findOne($tree->SelNode->tm_id);
        if ($tmnode->tm_id == 0) {
            $result = $tree->db->createCommand("SELECT tm_id FROM sys_template WHERE node_id = " . $tree->SelNode->tm_id . " and tm_ismain = 1");
        } else {
            $result = $tree->db->createCommand("SELECT tm_id FROM sys_template WHERE node_id = " . $tmnode->tm_id . " and tm_ismain = 1");
        }
    }
    $row = $result->queryOne();
} else {
    $row["tm_id"] = $tm_id;
    $row["leaf_id"] = $leaf_id;
}



echo Yii::$app->controller->renderPartial("printRows", array('param' => $param, 'prow' => $row, 'tree' => $tree), true);


if ($item->tree_readonly == 0) {
    echo "</form>";
}