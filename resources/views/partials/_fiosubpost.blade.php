<div id="{{ $post->id }}" class="fio-subpost">
    @if($post->modpost) <p class="modpost">### Administrador ###</p>  
    @else <span class="anonpost-title">Anônimo</span> 
    @endif  
    @if($post->sage) <span class="sage-text">[sage]</span> @endif
    @if($post->mostra_countryflag)
    @include('partials._countryflag', ['anao' => $post->anao])
    @endif
    <strong class="assunto">{{ $post->assunto }}</strong> 
    <i>{{ $post->data_post }}</i>
    <u>Nro <a class="a-nro-post">{{ $post->id }}</a></u> 
    <a class="mini-btn btn-report" data-id-post="{{ $post->id }}" data-toggle="modal" data-target="#modalReport">
        <span data-toggle="tooltip" data-placement="top" title="Denunciar" class="glyphicon glyphicon-exclamation-sign"></span>
    </a>
    @include('partials._deletepost', ['siglaBoard' => $siglaBoard, 'postIdDel' => $post->id, $viewOnly])                    
    @if(Auth::check())
    <a class="mini-btn btn-ban" data-id-post="{{ $post->id }}" data-toggle="modal" data-target="#modalBan"><span data-toggle="tooltip" data-placement="top" title="Banir usuário" class="glyphicon glyphicon-ban-circle"></span></a>  
    @endif
    <br>

    <div class="row">
        <div class="col-sm-12">
            <div class="div-arquivos-anexos">
            @include('partials._postarquivos', ['arquivos' => $post->arquivos, 'siglaBoard' => $siglaBoard])

    @foreach ($post->ytanexos as $anx)
    <iframe width="220" height="220"
        src="https://www.youtube.com/embed/{{ $anx->ytcode }}">
    </iframe> 
    @endforeach
    </div>

            <span id="pc{{ $post->id }}"  class="quebra-texto post-conteudo">{!! substr($post->conteudo, 0, 500) !!}</span>
            @if(strlen($post->conteudo) >= 500)
                <span id="pcf{{ $post->id }}" class="quebra-texto post-conteudo-full" hidden>{!! $post->conteudo !!}</span><br><br>
                <a onclick="expandirContrairConteudoPost({{ $post->id }})">[Expandir Conteudo]</a>
            @endif
        </div>
    </div>
    @if($post->ban) @include('partials._banp', ['ban' => $post->ban])  @endif
    <br>
</div>