<div class="modal fade" id="mes_edit_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="mes_del_label">Удалить сообщение?</h4>
      </div>        
        <div class="modal-body" style="display: none;">
        <form>          
          <div class="form-group">
            <label for="message-text" class="control-label">Сообщение:</label>
            <textarea class="form-control" id="mes_edit_area"></textarea>
          </div>
        </form>
      </div>      
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Отмена</button>
        <button type="button" class="btn btn-primary chat_edit_confirm" mes_id="" data-dismiss="modal">Сохранить</button>
      </div>
    </div>
  </div>
</div>