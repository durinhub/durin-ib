@extends('main')


@section('titulo', $nomeib)

@section('stylesheets')
    <link rel="stylesheet" href="/css/style.css" >
@stop

@section('conteudo')
<div class="container-fluid snow-container">

@if(isset($regras))
@include('partials._rulesdiv')
@endif

@if($configuracaos->carteira_doacao)
    <br>
    <div class="row">
        <div class="col-sm-2"></div>
        <div class="col-sm-8 text-center div-indice"> 
        <h4><span class="free-text">Doações para pagamento de hospedagem e domínio:</span></h4>
        <div class="quebra-texto"><a href="https://getmonero.org/" target="_blank">{{ $configuracaos->carteira_doacao }}</a></div>
        <div class="text-center">
            <img src="/storage/res/doacao-monero.png" alt="qrcode">
        </div> <br><br>
        
        </div>
        <div class="col-sm-2"></div>
    </div>
@endif

@if(isset($noticias))
    <br>
    <hr>
    @foreach($noticias as $noticia)
        <div class="row">
            <div class="col-sm-2"></div>
            <div class="col-sm-8 div-indice"> 
            @if(Auth::check() && ($noticia->autor_id === Auth::id() || Auth::user()->canDo()))
                <a 
                data-toggle="tooltip" 
                data-placement="top" 
                title="Editar noticia" 
                class="mini-btn" 
                href="/editnoticia/{{ $noticia->id }}"><span class="glyphicon glyphicon-pencil"></span></a>&nbsp;
                <a 
                data-toggle="tooltip" 
                data-placement="top" 
                title="Deletar noticia" 
                class="mini-btn" 
                href="/deletenoticia/{{ $noticia->id }}"><span class="glyphicon glyphicon-remove"></span></a>
                <br>
            @endif
            <h2>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span class="free-text">{{ $noticia->assunto }}</span></h2>
                <div class="">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                <span class="free-text">{{ $noticia->conteudo }}</span><br><br>
                </div>
                <small class="noticia-data">{{ $noticia->autor->name }}, {{ $noticia->data_post }}</small><br>
            </div>
            <div class="col-sm-2"></div>
        </div>
        <br>
    @endforeach
@endif

</div>

@endsection


@section('scripts')
@endsection