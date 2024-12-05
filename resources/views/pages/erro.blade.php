@extends('main')


@section('titulo', $nomeib)

@section('stylesheets')
    <link rel="stylesheet" href="/css/style.css" >
@stop

@section('conteudo')
<div class="container-fluid">
    <div class="row">
        <div class="col-sm-2"></div>
        <div class="col-sm-8 text-center">
            <h1>{{ $msgErro }}</h1>
        </div>
        <div class="col-sm-2"></div>
    </div>
    <div class="row">
        <div class="col-sm-4"></div>
        <div class="col-sm-4 text-center">
            <a class="btn btn-info" href="{{ URL::previous() }}">Voltar</a>
        </div>
        <div class="col-sm-4"></div>
    </div>
    
</div>
@endsection
