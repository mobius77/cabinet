<!-- Direct Chat -->
<div class="row" id="helpdesk">
    <div class="col-md-12">
        <!-- DIRECT CHAT SUCCESS -->
        <div class="box box-success direct-chat direct-chat-success">
            <div class="box-header with-border">
                <h3 class="box-title">Повідомлення</h3>
                <div class="box-tools pull-right">
                    
                    <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                    </button>
                </div>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
                <!-- Conversations are loaded here -->
                <div class="direct-chat-messages" id="chat-messages">
                    <?= Yii::$app->controller->renderPartial('chat_items',['object'=>$object, 'model'=>$model,'chat'=>$chat]) ?>
                </div>
                <!--/.direct-chat-messages-->
                <!-- /.direct-chat-pane -->
            </div>
            <!-- /.box-body -->
            <div class="box-footer">
                <form action="/cabinet/add-dial" onsubmit="return my_chat_submit();" method="post" id="my-chat-form">
                    <div class="input-group">
                        <input type="text" id="ch_mes" name="message" placeholder="Повідомлення ..." class="form-control">
                        <span class="input-group-btn">
                            <button type="submit" class="btn btn-success btn-flat">Відправити</button>
                        </span>
                    </div>
                    <input type="hidden" name="obj_id" value="<?= $obj_id ?>" />
                    <input type="hidden" name="obj" value="<?= $object ?>" />
                    <input type="hidden" name="<?= Yii::$app->request->csrfParam; ?>" value="<?= Yii::$app->request->csrfToken; ?>" />
                </form>
            </div>
            <!-- /.box-footer-->
        </div>
        <!--/.direct-chat -->
    </div>
    <!-- /.col -->
</div>
<!-- /.row -->