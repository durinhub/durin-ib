<div id="modalDeleteBoard" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Deletar board</h4>
      </div>
      <div class="modal-body">
          <h1>Tem certeza que deseja deletar a board {{ $siglaBoard }}?</h1>
          <p>Serão deletados todos seus posts e arquivos. Esta ação não pode ser desfeita.</p>
      </div>
      <div class="modal-footer">
        <a href="/boards/deleteboard/{{ $siglaBoard }}" class="btn btn-danger">Deletar board</a>
        <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
      </div>
    </div>

  </div>
</div>