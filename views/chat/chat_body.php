<?php
use common\models\User;
?>

<?php

foreach ($mes_all as $mes_one) {
    $author = User::find()->where(['id' => $mes_one->otp_id])->one();
    if ($author->id == Yii::$app->user->id) {
        $msg_class = 'my_c_msg';
    } else {
        $msg_class = 'oth_c_msg';
    }
    ?>
    <?= Yii::$app->controller->renderPartial("msg_block", ['mes_one' => $mes_one, 'author' => $author, 'msg_class' => $msg_class]); ?> 


<?php }
?>
