@foreach($posts as $ind=>$post)
@if($ind === 0) <div id="{{ $post->id }}" class="fio" data-sigla-board="{{ $siglaBoard }}">
@else <div id="{{ $post->id }}" class="fio-subpost"> @endif

@if($post->pinado) <span class="glyphicon glyphicon-pushpin"></span> @endif 
@if($post->trancado) <span class="glyphicon glyphicon-lock"></span> @endif 
@if($post->modpost)   <p class="modpost">### Administrador ###</p> @else <span class="anonpost-title">Anônimo</span> @endif  
@if($post->sage) <span class="sage-text">[sage]</span> @endif
@if($post->mostra_countryflag)
@include('partials._countryflag', ['anao' => $post->anao])
@endif
<strong class="assunto">{{ $post->assunto }} </strong> 
 <i>{{ $post->data_post }}</i>
 <u>Nro <a class="a-nro-post">{{ $post->id }}</a></u>
 
<a class="mini-btn btn-report" data-id-post="{{ $post->id }}" data-toggle="modal" data-target="#modalReport"><span data-toggle="tooltip" data-placement="top" title="Denunciar" class="glyphicon glyphicon-exclamation-sign"></span></a> 
@if($ind === 0) 
<a data-toggle="tooltip" data-placement="top" title="Voltar" class="mini-btn" href="/boards/{{ $siglaBoard }}"><span class="glyphicon glyphicon-circle-arrow-left"></span></a>  
@endif 
@include('partials._deletepost', ['siglaBoard' => $siglaBoard, 'postIdDel' => $post->id, 'viewOnly' => false])     
@if(Auth::check()) 
    @if($ind === 0) 
        
        @if($post->pinado)
            <a data-toggle="tooltip" data-placement="top" title="Despinar fio" href="/boards/pinarpost/{{ $post->board }}/{{ $post->id }}/0" class="mini-btn"><span class="glyphicon glyphicon-pushpin"></span></a>
        @elseif(!$post->pinado)
        <a data-toggle="tooltip" data-placement="top" title="Pinar fio" href="/boards/pinarpost/{{ $post->board }}/{{ $post->id }}/1" class="mini-btn"><span class="glyphicon glyphicon-pushpin"></span></a> 
        @endif
         
        @if($post->trancado)
            <a data-toggle="tooltip" data-placement="top" title="Destrancar fio" href="/boards/trancarpost/{{ $post->board }}/{{ $post->id }}/0" class="mini-btn"><span class="glyphicon glyphicon-lock"></span></a>
        @elseif(!$post->trancado)
        <a data-toggle="tooltip" data-placement="top" title="Trancar fio" href="/boards/trancarpost/{{ $post->board }}/{{ $post->id }}/1" class="mini-btn"><span class="glyphicon glyphicon-lock"></span></a> 
        @endif
    <a class="mini-btn btn-mover-post" data-id-post="{{ $post->id }}" data-toggle="modal" data-target="#modalMoverPost"><span data-toggle="tooltip" data-placement="top" title="Mover postagem" class="glyphicon glyphicon-circle-arrow-right"></span></a> 
    @endif
    <a data-toggle="modal" data-placement="top" title="Banir usuário" class="mini-btn btn-ban" data-id-post="{{ $post->id }}" data-target="#modalBan"><span class="glyphicon glyphicon-ban-circle"></span></a> 

@endif <br>
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

        <span class="post-conteudo quebra-texto">{!! $post->conteudo !!}</span>
    </div>
</div>
@if($post->ban) <p class="ban-msg">({{ $post->ban->motivo }})</p>  @endif
@if($ind !== 0) </div> @endif
@endforeach
</div>
<div class="row">
    <div class="col-sm-3"></div>
    <div class="col-sm-3"><a href="{{ Request::url() }}#div-form">[Voltar para o topo]</a></div>
    <div class="col-sm-3"></div>
    <div class="col-sm-3"></div>
</div>