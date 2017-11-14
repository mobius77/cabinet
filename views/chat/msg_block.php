<?php ?>
<div id="msg_item<?= $mes_one->cm_id ?>" class="chat-item  item <?= $msg_class ?>" style="position: relative;">
    <img src="/img/user3-128x128.jpg" alt="user image" class="online">

    <p class="message">
        <a class="name">
            <small class="text-muted pull-right">
                <i class="fa fa-clock-o"></i>
                <?=
                date("Y-m-d", strtotime($mes_one->cm_date)) < date("Y-m-d") ?
                        date("d.m.Y", strtotime($mes_one->cm_date)) : ''
                ?>
                <?= date("H:i", strtotime($mes_one->cm_date)) ?>
            </small>

            <?= $author->user_firstname ?>                                    
        </a>
        <span id="msg_block_<?= $mes_one->cm_id ?>"><?= $mes_one->cm_text ?></span>            
    </p>
    <?php
    
    if ($msg_class=='my_c_msg') { ?>
    <div class="btn-group edit_btn">
        
        <a class="btn btn-default btn-xs edit_btn chat-btn-edit" data-toggle="modal" data-target="#mes_edit_modal"
           data-cm_id="<?= $mes_one->cm_id ?>"><i class="fa fa-pencil"></i>
        </a>
        <a class="btn btn-default btn-xs edit_btn chat-btn-del" data-toggle="modal" data-target="#mes_edit_modal"
           data-cm_id="<?= $mes_one->cm_id ?>"><i class="fa fa-trash"></i>
        </a>
        
    </div>
    <?php } ?>

</div>