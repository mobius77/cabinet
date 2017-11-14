<?php


if ($chat != null) {
    foreach ($chat as $mes) {
if (($mes->status==0)&&($mes->user_id!=Yii::$app->user->id )) {
    $mes->status = 1;
    $mes->save();
}

        if (Yii::$app->user->id == $mes->user_id) {
            ?>

            <div class="direct-chat-msg right">
                <div class="direct-chat-info clearfix">
                    <span class="direct-chat-name pull-right"><?= $mes->user->user_firstname ?></span>
                    <span class="direct-chat-timestamp pull-left"><?= $mes->d_date ?></span>
                </div>
                <div class="direct-chat-text">
                    <?= $mes->content ?>
                </div>
            </div>

            <?php
        } else {
            ?> 
            <div class="direct-chat-msg">
                <div class="direct-chat-info clearfix">
                    <span class="direct-chat-name pull-left"><?= $mes->user->user_firstname ?></span>
                    <span class="direct-chat-timestamp pull-right"><?= $mes->d_date ?></span>
                </div>
                <!-- /.direct-chat-info -->

                <div class="direct-chat-text">
                    <?= $mes->content ?>
                </div>
                <!-- /.direct-chat-text -->
            </div>

            <?php
        }
    }
}
?>
