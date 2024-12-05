<div id="modalConf" class="modal fade" role="dialog">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close modal-close-btn" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Configurações</h4>
      </div>
      <div class="modal-body">
        <form action="/action_page.php">
            <p><span class="free-text">Escolha o tema:</span></p>
            <input type="radio" id="iblight" name="ibtema" value="iblight" onchange="setaTema('iblight');$('#modalConf').modal('hide');">
            <label for="iblight">Anões Light</label><br>
            <input type="radio" id="ibdark" name="ibtema" value="ibdark" onchange="setaTema('ibdark');$('#modalConf').modal('hide');">
            <label for="ibdark">Anões Dark</label><br>
            <input type="radio" id="yotsuba" name="ibtema" value="yotsuba" onchange="setaTema('yotsuba');$('#modalConf').modal('hide');">
            <label for="yotsuba">Yotsuba</label><br>
            <input type="radio" id="yotsubab" name="ibtema" value="yotsubab" onchange="setaTema('yotsubab');$('#modalConf').modal('hide');">
            <label for="yotsubab">Yotsuba B</label><br>
            <input type="radio" id="semtema" name="ibtema" value="semtema" onchange="setaTema('semtema');$('#modalConf').modal('hide');">
            <label for="semtema">Sem tema</label><br>
        </form>
      </div>
    </div>

  </div>
</div>