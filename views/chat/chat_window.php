 <!-- Chat box -->
        <div class="box box-success" style="">
            <!--
            <div class="box-header">
                <i class="fa fa-comments-o"></i>
                <h3 class="box-title">Корпоративный чат</h3>                
            </div>
            -->
            <div class="box-body chat " id="chat-box" style="height: 70vh; overflow: auto;">                

                <!-- chat item -->

                <?php for ($i = 0; $i < 15; $i++) { ?>
                    <div class="item">
                        <img src="/img/user3-128x128.jpg" alt="user image" class="online">

                        <p class="message">
                            <a href="#" class="name">
                                <small class="text-muted pull-right"><i class="fa fa-clock-o"></i> 2:15</small>
                                Mike Doe
                            </a>
                            I would like to meet you to discuss the latest news about
                            the arrival of the new theme. They say it is going to be one the
                            best themes on the market
                        </p>
                        <!--
                        <div class="attachment">
                            <h4>Attachments:</h4>

                            <p class="filename">
                                Theme-thumbnail-image.jpg
                            </p>

                            <div class="pull-right">
                                <button type="button" class="btn btn-primary btn-sm btn-flat">Open</button>
                            </div>
                        </div>
                        <!-- /.attachment -->
                    </div>

                <?php } ?>
                <!-- /.item -->

                
            </div>
            <!-- /.chat -->
            <!--
            <div class="box-footer">
                
            </div>
            -->
        </div>
        <!-- /.box (chat box) -->
        
        <div class="col-lg-12">
        <div class="input-group">
                    <input class="form-control" placeholder="Type message...">

                    <div class="input-group-btn">
                        <button type="button" class="btn btn-success"><i class="fa fa-plus"></i></button>
                    </div>
                </div>
        </div>