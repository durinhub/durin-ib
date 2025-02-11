
<a href="/boards/{{$siglaBoard}}" class="link-boarda">
    <h1 class="board-header" id="id-board-{{ $siglaBoard }}"> /{{ $siglaBoard }}/ - {{ $descrBoard }} </h1>
</a>
<br>
<form id="form-novo-post" class="form-post" role="form" method="POST" enctype="multipart/form-data" action="{{ route('posts.store') }}">
{{ csrf_field() }}
<input type="hidden" name="siglaboard" value="{{ $siglaBoard }}">
<input type="hidden" name="insidepost" value="{{ $insidePost }}">
@if(Auth::check()) 
<div style="float: left; margin-bottom: 20px; margin-left: 15px;">
<span class="free-text">Modpost</span> <input type="checkbox" class="novo-post-form-item" name="modpost" value="modpost">
</div>
@endif
<input type="text" class="novo-post-form-item form-control" maxlength="255" placeholder="Assunto" name="assunto" >
<div class="row">
    <div class="col-sm-1"></div>
    <div class="col-sm-11">
        <div id="form-post-file-input-div">
            <div class="form-post-file-input-box">
                <div class="row">
                    <div class="col-sm-8" onclick="selecionaArquivo(this)"><label for="arquivos[]"><span class="glyphicon glyphicon-open-file"></span> Escolha um arquivo</label></div>
                    <div class="col-sm-2"><span class="free-text">Spoiler</span> <input name="arquivos-spoiler-1" type="checkbox" value="spoiler"></div>
                    <div class="col-sm-2"><button type="button" class="close" onclick="limpaInputFile(this)"><span class="glyphicon glyphicon-remove-sign"></span></button></div>
                </div>
                <input class="novo-post-form-item form-post-file-input" name="arquivos[]" type="file" onchange="addNovoInputFile(this, {{ $configuracaos->num_max_arq_post }})" hidden>

            </div>
        </div>
    </div>
</div>

<input type="text" class="novo-post-form-item form-control" maxlength="255" placeholder="Link(s) para vídeo(s) do youtube, separados por |" name="linkyoutube" id="linkyoutube">
<textarea class="novo-post-form-item form-control" id="novo-post-conteudo" placeholder="Mensagem" rows="5" maxlength="26300" name="conteudo" @if(isset($requiredConteudo) && $requiredConteudo) required oninvalid="this.setCustomValidity('Por favor, fale algo para abrir um fio')" oninput="setCustomValidity('')" @endif></textarea>
<div class="row">
    <div class="col-sm-12 password-section"><input type="password" class="novo-post-form-item form-control" maxlength="25" minlength="6" value="{{$anao->ultima_senhadel_usada}}" placeholder="Senha para deletar" id="senhadel" name="senhadel" ><span class="free-text">Senha para deletar</span><span id="togglePassword" class="glyphicon glyphicon-eye-open"></span></div>
</div>
<p style="margin-left: 15px;"><span class="free-text">Mime types: image/jpeg, image/png, image/gif, video/webm, video/mp4, audio/mpeg</span></p>
<div class="row">
    <div class="col-sm-3">
        <span class="free-text">Sage</span> <input type="checkbox" class="novo-post-form-item" name="sage" value="sage">
    </div>
    <div class="col-sm-3">
        @if($siglaBoard !== 'int')
        <span class="free-text">Mostra bandeira</span> <input type="checkbox" class="novo-post-form-item" name="mostra_countryflag" value="mostra_countryflag">
        @endif
    </div>
    <div class="col-sm-6">
        <input type="submit" value="Postar" class="mini-btn form-control">
    </div>
</div>

@if($configuracaos->captcha_ativado)
<br>
<div class="row">
    <div class="col-sm-1"></div>
    <div class="col-sm-4 text-center">
    {!! $captchaImage !!}
    </div>
    <div class="col-sm-7">
        <input type="text" name="captcha" class="novo-post-form-item" maxlength="{{ $captchaSize }}" required>
    </div>
        
</div><br>
@endif

</form>