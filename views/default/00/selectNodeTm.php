<?php

/* 
 * Показываем списоквозможных шаблонов для нового элемента дерева
 * Список содержится в $model: node_id, tm_name
 */

$opti = "edit_node"; 
foreach($_POST as $key => $value){ $$key=$value;}
foreach($_GET as $key => $value){ if (!isset($$key)) $$key=$value;}		
if($id_tree=="") $id_tree=1;

?>
<div id="popupw">
        <div class="opaco"></div>
        <div id="container" class="reg-block">
            <div class="cont-top">
                <div class="intra1">
                    <div class="intra2">
                        <a class="close" onclick="$('#popupw').hide(); $('#popupw').html(''); window.location.href = '/admin?id_tree=<?php echo $id_tree; ?>'; " href="javascript:void(0);"></a></div>
                </div>
            </div>
            <div class="cont-c">
                <div id="reg-box" class="field" style="display:block;">

<form class="forms" action="addnodeone" method="post" enctype="multipart/form-data" name="form_selectNodeTm" id="formtf">
  <table width="100%" border="0">
 
    <tr>
        <td align="center">Выберите шаблон для нового элемента:</td>
    </tr>

    <tr>
      <td align="center"><select name="tm_id"  class="input" style="width: 250px">";
                <?php    
                    foreach($model as $ro)        
			{
                            if ($lt==$ro["node_id"]) 
                                { ?> <option value="<?php echo $ro["node_id"];?>" selected><?php echo $ro["tm_name"];?></option> <?php }
                            else
                                { ?> <option value="<?php echo $ro["node_id"];?>"><?php echo $ro["tm_name"];?></option> <?php }
			 } ?>
            </select></td>
    </tr>
    <tr>
        <td>&nbsp;</td>
    </tr>
    <tr>
        <td>
            <?php
             foreach($model as $ro) {
                 if ($ro["tree_img"]!='') echo $ro["tm_name"].' - <img src="/admin/images/tree_img/'.$ro["tree_img"].'"><br>';
                 
             }
            
            ?>
        </td>
    </tr>
    <tr>
      <td align="center">
          <input type="hidden" NAME="id_tree" value="<?php echo $id_tree; ?>">
          <input type="submit" name="Submit" class="botton" value="Создать">
      </td>
    </tr>
  </table>
</form>

          </div>	
            </div>	

            <div class="cont-bt">
                <div class="intra1">
                    <div class="intra2"></div>
                </div>
            </div>
        </div>
</div>


   