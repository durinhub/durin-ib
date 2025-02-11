<div id="modalDeletePost" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Deletar post</h4>
      </div>
      <form action="{{ route('posts.destroy') }}" method="post" class="mini-btn-form" id="formModalDeletePost">
      {{ csrf_field() }}
        <input type="hidden" name="siglaBoard" value="{{ $siglaBoard }}">
        <input type="hidden" name="postId" id="postIdInputModal">
        <div class="modal-body">
            <h1 class="free-text">Tem certeza que deseja deletar o post <span id="postIdModalSpan"></span>?</h1>
            <p class="free-text">Serão deletados todos seus anexos. Esta ação não pode ser desfeita.</p>
        </div>
        <div class="modal-footer">
          <div class="row">
            <div class="col-sm-6"></div>
            <div class="col-sm-3"><button type="button" class="form-control btn mini-btn-revert" data-dismiss="modal">Cancelar</button></div>
            <div class="col-sm-3"><input onclick="enviaFormModalDeletePost(this)" id="aSubmitModalDeletePost" data-postid="" type="submit" value="Deletar" class="form-control btn mini-btn"></div>
          </div>
             

            
        </div>
      </form>
    </div>

  </div>
</div>