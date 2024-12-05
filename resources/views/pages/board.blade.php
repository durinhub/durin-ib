@extends('boardbase')


@section('titulo', $nomeBoard)

@section('adstop')
@include('partials._genericad', ['ad' => $ad1])
@endsection

@section('conteudo')

@include('partials._boardpostheader')
@include('partials._boardrules')
<hr>
@include('partials._boardposts')

@if(Auth::check())
    @if(Auth::user()->canDo())
    <div class="row">
        <div class="col-sm-4"></div>
        <div class="col-sm-4 text-center"><a data-toggle="modal" data-target="#modalDeleteBoard" class="btn btn-danger">Deletar board</a></div>
        <div class="col-sm-4"></div>
    </div><br>
    @endif
    @include('partials._modalban')
    @include('partials._modaldeleteboard')
    @include('partials._modalmoverpost')
@endif

@include('partials._modalreport')
@endsection

@section('adsbottom')
@include('partials._genericad', ['ad' => $ad2])
@endsection