<div class="container-fluid">
@foreach($posts as $post)
@if($post->lead_id === NULL)
<div id="{{ $post->id }}" class="fio" data-sigla-board="{{ $siglaBoard }}">
    @if($post->pinado) <span class="glyphicon glyphicon-pushpin"></span> @endif 
    @if($post->trancado) <span class="glyphicon glyphicon-lock"></span> @endif 
    @if($post->modpost) <p class="modpost">### Administrador ###</p>  
    @else <span class="anonpost-title">Anônimo</span> @endif 
    @if($post->sage) <span class="sage-text">[sage]</span> @endif
    @if($post->mostra_countryflag)
    @include('partials._countryflag', ['anao' => $post->anao])
    @endif
     <a class="a-responder" href="/boards/{{ $siglaBoard }}/{{ $post->id }}">
        <strong class="assunto">{{ $post->assunto }}</strong>  
        <i>{{ $post->data_post }} </i>
     </a>
     <u>Nro <a class="a-nro-post">{{ $post->id }}</a></u>
     <a class="mini-btn" onclick="hidePost({{ $post->id }})"><span class="glyphicon glyphicon-minus" title="Esconder post"></span></a>
     <a class="mini-btn btn-report" data-id-post="{{ $post->id }}" data-toggle="modal" data-target="#modalReport"><span data-toggle="tooltip" data-placement="top" title="Denunciar" class="glyphicon glyphicon-exclamation-sign"></span></a> 
     @include('partials._deletepost', ['siglaBoard' => $siglaBoard, 'postIdDel' => $post->id, 'viewOnly' => false])   
     <a data-toggle="tooltip" data-placement="top" title="Responder" class="mini-btn" href="/boards/{{ $siglaBoard }}/{{ $post->id }}">[Responder]</a> 
     <div class="divAddCitacoes" id="addCitacoes{{ $post->id }}"></div>
     <br>
    @if(Auth::check())
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

        <a class="mini-btn btn-ban" data-id-post="{{ $post->id }}" data-toggle="modal" data-target="#modalBan"><span data-toggle="tooltip" data-placement="top" title="Banir usuário">[Banir]</span></a> 
        <a class="mini-btn btn-mover-post" data-id-post="{{ $post->id }}" data-toggle="modal" data-target="#modalMoverPost"><span data-toggle="tooltip" data-placement="top" title="Mover postagem" class="glyphicon glyphicon-circle-arrow-right"></span></a> 
    
    @endif <br>
    
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
@if($post->ban) @include('partials._banp', ['ban' => $post->ban])<br>  @endif

    @php
        $sbtemp = $subPosts->where('lead_id', '=', $post->id);
        $k = 0;
    @endphp
    @if($sbtemp)
        @foreach($sbtemp as $sb)
            @if($k > sizeof($sbtemp) - ($configuracaos->num_subposts_post + 1))
                @include('partials._fiosubpost', ['post' => $sb, 'viewOnly' => false])
            @endif
            @php
                $k += 1;
            @endphp
        @endforeach
    @endif
    
</div>
<div id="hid{{ $post->id }}" hidden>
    <span class="anonpost-title">Expandir fio {{ $post->id }}</span>
    <a class="mini-btn" onclick="showPost({{ $post->id }})"><span class="glyphicon glyphicon-plus" title="Mostrar post"></span></a>
</div>
<hr>
@endif
@endforeach

@include('partials._divvoltatopo')
@if(isset($paginador))
    <br>
    <div class="row">
        <div class="col-sm-4"></div>
        <div class="col-sm-4 text-center">
            {!! $paginador->links('partials._paginador') !!}    
        </div>
        <div class="col-sm-4"></div>
    </div><br>
@endif
</div>