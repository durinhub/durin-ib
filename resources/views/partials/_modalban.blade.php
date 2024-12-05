<div id="modalBan" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Banir usuário</h4>
      </div>
        <form class="form-horizontal" role="form" method="POST" action="{{ route('bans.userban') }}">
            <div class="modal-body">
                <div class="row">
                    <div class="col-sm-1"></div>
                    <div class="col-sm-10">
                        {{ csrf_field() }}
                        <input type="text" class="novo-post-form-item form-control" maxlength="255" placeholder="Motivo" name="motivo">
                        Permaban: <input type="checkbox" class="novo-post-form-item" value="permaban" name="permaban">

                        <input type="number" class="novo-post-form-item form-control" placeholder="Qtdade de horas" min="1" max="24" name="nro_horas">
                        <input type="number" class="novo-post-form-item form-control" placeholder="Qtdade de dias" min="1" name="nro_dias">

                        <select name="board" class="novo-post-form-item form-control" maxlength="10" required>
                            <option value="{{ $siglaBoard }}">{{ $siglaBoard }}</option>
                            <option value="todas">Todas as boards</option>
                        </select>

                        <input type="hidden" name="siglaboard" value="{{ $siglaBoard }}">
                        <input type="hidden" id="idPostInput" name="idpost" value="">
                    </div>
                    <div class="col-sm-1"></div>

                </div>
            </div>
            <div class="modal-footer">
              <div class="row">
                <div class="col-sm-6"></div>
                <div class="col-sm-3"><button type="button" class="form-control btn mini-btn-revert" data-dismiss="modal">Cancelar</button></div>
                <div class="col-sm-3"><input type="submit" class="form-control btn mini-btn" value="Banir"></div>
              </div>
            </div>
        </form>
    </div>

  </div>
</div>