@extends('main')


@section('titulo', $nomeib)

@section('stylesheets')
    <link rel="stylesheet" href="/css/style.css" >
@stop

@section('conteudo')
<div class="container-fluid">
    <div class="row">
        <div class="col-sm-4"></div>
        
        <div class="col-sm-4 text-center div-regras">
            <h1>CATALOGO</h1>
        </div>
        
        <div class="col-sm-4"></div>
        
    </div>
    
    <span class="free-text">Board:</span>
    <div class="row">
        <div class="col-sm-12">
            <select id="select-board-catalogo">
                <option value="todas" selected>todas</option>
                @foreach($boards as $board)
                    @if(!$board->secreta)
                    <option value="{{ $board->sigla }}"> {{ $board->sigla }}</option>
                    @endif
                @endforeach
            </select>
        </div>
    </div>
    <br>
    <div class="row">
        <div class="col-sm-12">
            @foreach($posts as $post)
                @php
                    $vid = false;
                    if($post->arquivos && sizeof($post->arquivos) > 0){
                        $arq = $post->arquivos[0];
                    } else if($post->ytanexos && sizeof($post->ytanexos) > 0)
                    {
                        $arq = $post->ytanexos[0];
                        $vid = true;
                    }
                    else 
                    {
                        $arq = new \App\Models\Arquivo;
                        $arq->spoiler = true;
                    }
                    
                @endphp
                <div class="catalogo-post-div text-center catalogo-post-div-board-{{ $post->board }}">
                    <span style="font-weight: bold;">
                    <span class="free-text"> @if($post->assunto) {{ substr($post->assunto, 0, 15) }} | @endif /{{ $post->board }}/</span>
                    </span>
                    <br>
                    <a href="/boards/{{ $post->board }}/{{ $post->id }}" target="_blank">
                    @if(!$vid)
                    <img class="img-responsive img-thumbnail" 
                    @if($arq->spoiler) src="{{ \Storage::url('res/spoiler.png') }}"
                    @elseif($arq->mime === "audio/mpeg") src="{{ \Storage::url('res/music.png') }}"
                    @elseif($arq->mime === "video/mp4" || $arq->mime === "video/webm") src="{{ \Storage::url('res/video.png') }}"
                    @else src="{{ \Storage::url($arq->filename) }}"
                    @endif
                    width="150px" height="150px" >
                    @else
                    <img class="img-responsive img-thumbnail" src="//img.youtube.com/vi/{{$arq->ytcode}}/0.jpg" width="150px" height="150px" >
                    @endif
                    </a>
                    <div class="quebra-texto">
                    <span class="free-text">{!! strip_tags(substr($post->conteudo, 0, 80), '<br>') !!}</span>
                    </div>
                    <hr>
                </div>
            @endforeach
        </div>
    </div>
    
</div>
@endsection


@section('scripts')
@endsection